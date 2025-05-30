<?php

namespace Devio\Pipedrive\Http;

use Devio\Pipedrive\Pipedrive;
use Devio\Pipedrive\PipedriveTokenStorage;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;

class PipedriveClient implements Client
{
    /**
     * The Guzzle client instance.
     */
    protected GuzzleClient $client;

    /**
     * Oauth flag
     */
    protected bool $isOauth = false;

    public const DEFAULT_BODY_FORMAT = RequestOptions::JSON;

    /**
     * GuzzleClient constructor.
     *
     * @param string $url
     * @param mixed  $credentials
     */
    public function __construct(string $url, $credentials)
    {
        [$headers, $query] = [[], []];

        if (gettype($credentials) === 'object') {
            $this->isOauth = true;
            $headers['Authorization'] = 'Bearer ' . $credentials->getAccessToken();
        } else {
            $query['api_token'] = $credentials;
        }

        $this->client = new GuzzleClient(
            [
                'base_uri'        => $url,
                'allow_redirects' => false,
                'headers'         => $headers,
                'query'           => $query,
            ]
        );
    }

    /**
     * Create an OAuth client.
     *
     * @param string                $url
     * @param PipedriveTokenStorage $storage
     * @param Pipedrive             $pipedrive
     *
     * @return PipedriveClient
     */
    public static function OAuth(
        string $url,
        PipedriveTokenStorage $storage,
        Pipedrive $pipedrive
    ): self {
        $token = $storage->getToken();

        if (!$token instanceof \Devio\Pipedrive\PipedriveToken || !$token->valid()) {
            $pipedrive->OAuthRedirect();
        }

        $token->refreshIfNeeded($pipedrive);

        return new self($url, $token);
    }

    /**
     * Perform a GET request.
     *
     * @param string $url
     * @param array  $parameters
     *
     * @return Response
     * @throws GuzzleException
     */
    public function get(string $url, array $parameters = []): Response
    {
        $options = $this->getClient()->getConfig();

        Arr::set($options, 'query', array_merge($parameters, $options['query']));

        // For this particular case we have to include the parameters into the
        // URL query. Merging the request default query configuration to the
        // request parameters will make the query key contain everything.
        return $this->execute(new GuzzleRequest('GET', $url), $options);
    }

    /**
     * Perform a POST request.
     *
     * @param string $url
     * @param array  $parameters
     *
     * @return Response
     */
    public function post(string $url, array $parameters = []): Response
    {
        $request = new GuzzleRequest('POST', $url);
        $form = self::DEFAULT_BODY_FORMAT;

        // If any file key is found, we will assume we have to convert the data
        // into the multipart array structure. Otherwise, we will perform the
        // request as usual using the form_params with the given parameters.
        if (isset($parameters['file'])) {
            $form = 'multipart';
            $parameters = $this->multipart($parameters);
        }

        if (isset($parameters['json'])) {
            $form = RequestOptions::JSON;
            $parameters = Arr::except($parameters, RequestOptions::JSON);
        }

        return $this->execute($request, [$form => $parameters]);
    }

    /**
     * Convert the parameters into a multipart structure.
     *
     * @param array $parameters
     * @return array
     */
    protected function multipart(array $parameters): array
    {
        if (! ($file = $parameters['file']) instanceof \SplFileInfo) {
            throw new \InvalidArgumentException('File must be an instance of \SplFileInfo.');
        }

        $result = [];
        $content = file_get_contents($file->getPathname());

        foreach (Arr::except($parameters, 'file') as $key => $value) {
            $result[] = ['name' => $key, 'contents' => (string) $value];
        }

        // Will convert every element of the array into a format accepted by the
        // multipart encoding standards. It will also add a special item which
        // includes the file key name, the content of the file and its name.
        $result[] = ['name' => 'file', 'contents' => $content, 'filename' => $file->getFilename()];

        return $result;
    }

    /**
     * Perform a PUT request.
     *
     * @param string $url
     * @param array  $parameters
     *
     * @return Response
     */
    public function put(string $url, array $parameters = []): Response
    {
        $request = new GuzzleRequest('PUT', $url);

        return $this->execute($request, [self::DEFAULT_BODY_FORMAT => $parameters]);
    }

    /**
     * Perform a PATCH request.
     *
     * @param string $url
     * @param array  $parameters
     *
     * @return Response
     */
    public function patch(string $url, array $parameters = []): Response
    {
        $request = new GuzzleRequest('PATCH', $url);
        $form = self::DEFAULT_BODY_FORMAT;

        // If any file key is found, we will assume we have to convert the data
        // into the multipart array structure. Otherwise, we will perform the
        // request as usual using the form_params with the given parameters.
        if (isset($parameters['file'])) {
            $form = 'multipart';
            $parameters = $this->multipart($parameters);
        }

        if (isset($parameters['json'])) {
            $form = RequestOptions::JSON;
            $parameters = Arr::except($parameters, RequestOptions::JSON);
        }

        return $this->execute($request, [$form => $parameters]);
    }

    /**
     * Perform a DELETE request.
     *
     * @param string $url
     * @param array  $parameters
     *
     * @return Response
     */
    public function delete(string $url, array $parameters = []): Response
    {
        $request = new GuzzleRequest('DELETE', $url);

        return $this->execute($request, [self::DEFAULT_BODY_FORMAT => $parameters]);
    }

    /**
     * Execute the request and returns the Response object.
     *
     * @param GuzzleRequest            $request
     * @param array                    $options
     * @param Client|GuzzleClient|null $client
     *
     * @return Response
     * @throws GuzzleException
     */
    protected function execute(
        GuzzleRequest $request,
        array $options = [],
        Client|GuzzleClient|null $client = null
    ): Response {
        $client = $client ?: $this->getClient();

        // We will just execute the given request using the default or given client
        // and with the passed options wich may contain the query, body vars, or
        // any other info. Both OK and fail will generate a response object.
        try {
            $response = $client->send($request, $options);
        } catch (BadResponseException $badResponseException) {
            $response = $badResponseException->getResponse();
        }

        // As there are a few responses that are supposed to perform the
        // download of a file, we will filter them. If found, we will
        // set the file download URL as the response content data.
        $body = $response->getHeader('location') !== []
            ? $response->getHeader('location')
            : json_decode($response->getBody());

        return new Response(
            $response->getStatusCode(),
            $body,
            $response->getHeaders()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function isOauth(): bool
    {
        return $this->isOauth;
    }

    /**
     * Return the client.
     *
     * @return GuzzleClient
     */
    public function getClient(): GuzzleClient
    {
        return $this->client;
    }
}

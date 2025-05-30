<?php

namespace Devio\Pipedrive\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Post\PostFile;
// use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Message\Request as GuzzleRequest;
use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Message\RequestInterface;

class PipedriveClient4 implements Client
{
    /**
     * The Guzzle client instance.
     *
     * @var GuzzleClient
     */
    protected GuzzleClient $client;

    protected array $queryDefaults = [];

    /**
     * Oauth flag
     *
     * @var bool
     */
    protected bool $isOauth = false;

    /**
     * GuzzleClient constructor.
     *
     * @param string $url
     * @param $token
     */
    public function __construct(string $url, $token)
    {
        $this->client = new GuzzleClient(
            [
                'base_url' => $url,
                'defaults' => [
                    'query' => ['api_token' => $token],
                ]
            ]
        );
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
        $request = $this->getClient()->createRequest('GET', $url, ['query' => $parameters]);

        return $this->execute($request);
    }

    /**
     * Perform a POST request.
     *
     * @param string $url
     * @param array  $parameters
     *
     * @return Response
     * @throws GuzzleException
     */
    public function post(string $url, array $parameters = []): Response
    {
        // If any file key is found, we will assume we have to convert the data
        // into the multipart array structure. Otherwise, we will perform the
        // request as usual using the form_params with the given parameters.
        if (isset($parameters['file'])) {
            $parameters = $this->multipart($parameters);
        }

        $request = $this->getClient()->createRequest('POST', $url, ['body' => $parameters]);

        return $this->execute($request);
    }

    /**
     * Convert the parameters into a multipart structure.
     *
     * @param array $parameters
     * @return array
     */
    protected function multipart(array $parameters)
    {
        if (! ($file = $parameters['file']) instanceof \SplFileInfo) {
            throw new \InvalidArgumentException('File must be an instance of \SplFileInfo.');
        }

        $parameters['file'] = new PostFile('file', file_get_contents($file->getPathname()), $file->getFilename());

        return $parameters;
    }

    /**
     * Perform a PUT request.
     *
     * @param string $url
     * @param array  $parameters
     *
     * @return Response
     * @throws GuzzleException
     */
    public function put(string $url, array $parameters = []): Response
    {
        $request = $this->getClient()->createRequest('PUT', $url, ['body' => $parameters]);

        return $this->execute($request);
    }

    /**
     * Perform a DELETE request.
     *
     * @param       $url
     * @param array $parameters
     *
     * @return Response
     * @throws GuzzleException
     */
    public function delete($url, array $parameters = []): Response
    {
        $request = $this->getClient()->createRequest('DELETE', $url, ['body' => $parameters]);

        return $this->execute($request);
    }

    /**
     * Execute the request and returns the Response object.
     *
     * @param GuzzleRequest     $request
     * @param GuzzleClient|null $client
     *
     * @return Response
     * @throws GuzzleException
     */
    protected function execute(
        GuzzleRequest $request,
        GuzzleClient|null $client = null
    ): Response {
        $client = $client ?: $this->getClient();

        // We will just execute the given request using the default or given client
        // and with the passed options wich may contain the query, body vars, or
        // any other info. Both OK and fail will generate a response object.
        try {
            $response = $client->send($request);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        }
        // As there are a few responses that are supposed to perform the
        // download of a file, we will filter them. If found, we will
        // set the file download URL as the response content data.
        $body = $response->getHeader('location') ?: json_decode($response->getBody());

        return new Response(
            $response->getStatusCode(), $body, $response->getHeaders()
        );
    }

    /**
     * @inheritDoc
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
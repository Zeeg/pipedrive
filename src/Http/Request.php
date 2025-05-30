<?php

namespace Devio\Pipedrive\Http;

use Devio\Pipedrive\Builder;
use Devio\Pipedrive\Exceptions\ItemNotFoundException;
use Devio\Pipedrive\Exceptions\PipedriveException;

/**
 * @method Response get($type, $target, $options = [])
 * @method Response post($type, $target, $options = [])
 * @method Response put($type, $target, $options = [])
 * @method Response patch($type, $target, $options = [])
 * @method Response delete($type, $target, $options = [])
 */
class Request
{
    /**
     * The Builder instance.
     */
    protected Builder $builder;

    /**
     * Request constructor.
     *
     * @param Client $client
     */
    public function __construct(/**
         * The Http client instance.
         */
        protected Client $client
    ) {
        $this->builder = $this->client->isOauth() ? Builder::OAuth() : new Builder();
    }

    /**
     * Prepare and run the query.
     *
     * @param string $type
     * @param string $target
     * @param array  $options
     *
     * @return Response
     * @throws ItemNotFoundException
     * @throws PipedriveException
     */
    protected function performRequest(string $type, string $target, array $options = []): Response
    {
        $this->builder->setTarget($target);

        $endpoint = $this->builder->buildEndpoint($options);
        // We will first extract the parameters required by the endpoint URI. Once
        // got, we can create the URI signature replacing those parameters. Any
        // other info will be part of the query and placed in URL or headers.
        $query = $this->builder->getQueryVars($options);

        return $this->executeRequest($type, $endpoint, $query);
    }

    /**
     * Execute the query against the HTTP client.
     *
     * @param string $type
     * @param string $endpoint
     * @param array  $query
     *
     * @return Response
     *
     * @throws ItemNotFoundException
     * @throws PipedriveException
     */
    protected function executeRequest(string $type, string $endpoint, array $query = []): Response
    {
        return $this->handleResponse(
            call_user_func_array([$this->client, $type], [$endpoint, $query])
        );
    }

    /**
     * Handling the server response.
     *
     * @param Response $response
     *
     * @return Response
     *
     * @throws ItemNotFoundException
     * @throws PipedriveException
     */
    protected function handleResponse(Response $response): Response
    {
        $content = $response->getContent();

        // If the request did not succeed, we will notify the user via Exception
        // and include the server error if found. If it is OK and also server
        // inludes the success variable, we will return the response data.
        if (!isset($content) || $response->getStatusCode() != 302 && !$response->isSuccess()) {
            if ($response->getStatusCode() == 404) {
                throw new ItemNotFoundException($content->error ?? 'Error unknown.');
            }

            if ($response->getStatusCode() == 401) {
                throw new PipedriveException(
                    $content->error ?? 'Unauthorized',
                    $response->getStatusCode()
                );
            }

            if ($response->getStatusCode() == 403) {
                throw new PipedriveException(
                    $content->error ?? 'Forbidden',
                    $response->getStatusCode()
                );
            }

            $this->throwPipedriveException($content);
        }

        return $response;
    }

    /**
     * Throws PipedriveException with message depending on content.
     *
     * @param mixed $content
     *
     * @throws PipedriveException
     */
    protected function throwPipedriveException(mixed $content)
    {
        if ((!$content instanceof \stdClass) || !isset($content->error)) {
            throw new PipedriveException('Error unknown.');
        }

        if (property_exists($content->error, 'message')) {
            throw new PipedriveException($content->error->message);
        }

        throw new PipedriveException($content->error);
    }

    /**
     * Set the endpoint name.
     *
     * @param string $resource
     */
    public function setResource(string $resource): void
    {
        $this->builder->setResource($resource);
    }

    /**
     * Set the token.
     *
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->builder->setToken($token);
    }

    /**
     * Pointing request operations to the request performer.
     *
     * @param string $name
     * @param array  $args
     *
     * @return Response|null
     */
    public function __call(string $name, array $args = []): ?Response
    {
        if (in_array($name, ['get', 'post', 'put', 'patch', 'delete'])) {
            $options = empty($args[1]) ? [] : $args[1];

            // Will pass the function name as the request type. The second argument
            // is the URI passed to the method. The third parameter will include
            // the request option values array that is stored in index 1.
            try {
                return $this->performRequest($name, $args[0], $options);
            } catch (ItemNotFoundException | PipedriveException) {
                // No action
            }
        }

        return null;
    }
}

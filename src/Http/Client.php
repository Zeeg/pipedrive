<?php

namespace Devio\Pipedrive\Http;

interface Client
{
    /**
     * Perform a GET request.
     *
     * @param string $url
     * @param array  $parameters
     *
     * @return Response
     */
    public function get(string $url, array $parameters = []): Response;

    /**
     * Perform a POST request.
     *
     * @param string $url
     * @param array  $parameters
     *
     * @return Response
     */
    public function post(string $url, array $parameters = []): Response;

    /**
     * Perform a PUT request.
     *
     * @param string $url
     * @param array $parameters
     *
     * @return Response
     */
    public function put(string $url, array $parameters = []): Response;

    /**
     * Perform a DELETE request.
     *
     * @param string $url
     * @param array  $parameters
     *
     * @return Response
     */
    public function delete(string $url, array $parameters = []): Response;

    /**
     * Check if the client is configured for OAuth.
     *
     * @return bool
     */
    public function isOauth(): ?bool;
}

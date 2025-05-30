<?php

namespace Devio\Pipedrive\Http;

class Response
{
    /**
     * Response constructor.
     *
     * @param int   $statusCode
     * @param mixed$content
     * @param array $headers
     */
    public function __construct(
        /**
         * The response code.
         */
        protected int $statusCode,
        /**
         * The response data.
         */
        protected mixed $content,
        /**
         * The response headers.
         */
        private readonly array $headers = []
    ) {
    }

    /**
     * Check if the request was successful.
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        if (! $this->getContent()) {
            return false;
        }

        return $this->getContent()->success;
    }

    /**
     * Get the request data.
     *
     * @return array|\stdClass|null
     */
    public function getData(): array|\stdClass|null
    {
        if ($this->isSuccess() && isset($this->getContent()->data)) {
            return $this->getContent()->data;
        }

        return null;
    }

    /**
     * Get the additional data array if any.
     *
     * @return mixed[]|\stdClass
     */
    public function getAdditionalData(): array|\stdClass|null
    {
        if ($this->isSuccess() && isset($this->getContent()->additional_data)) {
            return $this->getContent()->additional_data;
        }

        return null;
    }

    /**
     * Get the status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the content.
     *
     * @return mixed
     */
    public function getContent(): mixed
    {
        return $this->content;
    }

    /**
     * Get the headers array.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}

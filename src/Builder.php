<?php

namespace Devio\Pipedrive;

use Illuminate\Support\Arr;
use InvalidArgumentException;

class Builder
{
    /**
     * API base URL.
     */
    protected string $base = Pipedrive::PIPEDRIVE_API_URL . '{endpoint}';

    /**
     * Resource name.
     */
    protected string $resource = '';

    /**
     * Full URI without resource.
     */
    protected string $target = '';

    /**
     * The API token.
     */
    protected string $token;

    /**
     * OAuth enabled or disabled.
     */
    protected bool $isOauth = false;

    /**
     * Get the name of the URI parameters.
     *
     * @param string $target
     *
     * @return array
     */
    public function getParameters(string $target = ''): array
    {
        if (empty($target)) {
            $target = $this->getTarget();
        }

        preg_match_all('/:\w+/', $target, $result);

        return str_replace(':', '', Arr::flatten($result));
    }

    /**
     * Replace URI tags by the values in options.
     *
     * buildUri(':id', ['id' => 55', 'name' => 'foo'])
     * will give:
     * 'organizations/55'
     *
     * @param array $options
     *
     * @return string|null
     *
     */
    public function buildEndpoint(array $options = []): ?string
    {
        $endpoint = $this->getEndpoint();

        // Having the URI, we'll now replace every parameter preceed with a colon
        // character with the values matching the keys of the 'options' array. If
        // any of these parameters is not set we'll notify with an exception.
        foreach ($options as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            if (is_object($value)) {
                if (isset($value->id)) {
                    $value = $value->id;
                } else {
                    continue;
                }
            }

            $endpoint = preg_replace(sprintf('/:%s/', $key), (string) $value, (string) $endpoint);
        }

        if ($this->getParameters($endpoint) !== []) {
            throw new InvalidArgumentException('The URI contains unassigned params.');
        }

        return $endpoint;
    }

    /**
     * Check if OAuth is enabled.
     */
    public function isOauth(): bool
    {
        return $this->isOauth ?? false;
    }

    /**
     * Get a builder instance prepared for OAuth.
     */
    public static function OAuth(): self
    {
        $instance = new self();

        $instance->base = Pipedrive::PIPEDRIVE_API_DOMAIN . '{endpoint}';
        $instance->isOauth = true;

        return $instance;
    }

    /**
     * Get the full URI with the endpoint if any.
     *
     * @return string
     */
    protected function getEndpoint(): string
    {
        $result = $this->getTarget();

        if (!empty($this->getResource())) {
            $result = $this->getResource() . '/' . $result;
        }

        return rtrim($result, '/');
    }

    /**
     * Get the options that are not replaced in the URI.
     *
     * @param array $options
     *
     * @return array
     */
    public function getQueryVars(array $options = []): array
    {
        $vars = $this->getParameters();

        return Arr::except($options, $vars);
    }

    /**
     * Get the resource name.
     *
     * @return string
     */
    public function getResource(): string
    {
        return $this->resource;
    }

    /**
     * Set the resource name.
     *
     * @param string $name
     */
    public function setResource(string $name): void
    {
        $this->resource = $name;
    }

    /**
     * Get the target.
     *
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * Set the target.
     *
     * @param string $target
     */
    public function setTarget(string $target): void
    {
        $this->target = $target;
    }

    /**
     * Set the application token.
     *
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * Get the base URL.
     *
     * @return string
     */
    public function getBase(): string
    {
        return $this->base;
    }
}

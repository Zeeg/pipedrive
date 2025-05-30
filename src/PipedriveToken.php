<?php

namespace Devio\Pipedrive;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class PipedriveToken
{
    /**
     * The access token.
     *
     * @var string
     */
    protected string $accessToken;

    /**
     * The expiry date.
     *
     * @var string
     */
    protected string $expiresAt;

    /**
     * The refresh token.
     *
     * @var string
     */
    protected string $refreshToken;

    /**
     * PipedriveToken constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Get the access token.
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * Get the expiry date.
     *
     * @return string
     */
    public function expiresAt(): string
    {
        return $this->expiresAt;
    }

    /**
     * Get the refresh token.
     *
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * Check if the access token exists.
     *
     * @return bool
     */
    public function valid(): bool
    {
        return !empty($this->accessToken);
    }

    /**
     * Refresh the token only if needed.
     *
     * @param $pipedrive
     *
     * @throws GuzzleException
     */
    public function refreshIfNeeded($pipedrive): void
    {
        if (! $this->needsRefresh()) {
            return;
        }

        $client = new GuzzleClient([
            'auth' => [
                $pipedrive->getClientId(),
                $pipedrive->getClientSecret()
            ]
        ]);

        $response = $client->request('POST', Pipedrive::PIPEDRIVE_OAUTH_URL . 'oauth/token', [
            'form_params' => [
                'grant_type'   => 'refresh_token',
                'refresh_token' => $this->refreshToken
            ]
        ]);

        $tokenInstance = json_decode($response->getBody());

        $this->accessToken = $tokenInstance->access_token;
        $this->expiresAt = time() + $tokenInstance->expires_in;
        $this->refreshToken = $tokenInstance->refresh_token;

        $storage = $pipedrive->getStorage();

        $storage->setToken($this);
    }

    /**
     * Check if the token needs to be refreshed.
     *
     * @return bool
     */
    public function needsRefresh(): bool
    {
        return (int) $this->expiresAt - time() < 1;
    }
}

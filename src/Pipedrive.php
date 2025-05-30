<?php

namespace Devio\Pipedrive;

use Devio\Pipedrive\Http\Client;
use GuzzleHttp\Exception\GuzzleException;
use Devio\Pipedrive\Http\PipedriveClient4;
use Devio\Pipedrive\Resources\Activities;
use Devio\Pipedrive\Resources\ActivityFields;
use Devio\Pipedrive\Resources\ActivityTypes;
use Devio\Pipedrive\Resources\Authorizations;
use Devio\Pipedrive\Resources\CallLogs;
use Devio\Pipedrive\Resources\Currencies;
use Devio\Pipedrive\Resources\DealFields;
use Devio\Pipedrive\Resources\Deals;
use Devio\Pipedrive\Resources\EmailMessages;
use Devio\Pipedrive\Resources\EmailThreads;
use Devio\Pipedrive\Resources\Files;
use Devio\Pipedrive\Resources\Filters;
use Devio\Pipedrive\Resources\GlobalMessages;
use Devio\Pipedrive\Resources\Goals;
use Devio\Pipedrive\Resources\ItemSearch;
use Devio\Pipedrive\Resources\Leads;
use Devio\Pipedrive\Resources\NoteFields;
use Devio\Pipedrive\Resources\Notes;
use Devio\Pipedrive\Resources\OrganizationFields;
use Devio\Pipedrive\Resources\OrganizationRelationships;
use Devio\Pipedrive\Resources\Organizations;
use Devio\Pipedrive\Resources\PermissionSets;
use Devio\Pipedrive\Resources\PersonFields;
use Devio\Pipedrive\Resources\Persons;
use Devio\Pipedrive\Resources\Pipelines;
use Devio\Pipedrive\Resources\ProductFields;
use Devio\Pipedrive\Resources\Products;
use Devio\Pipedrive\Resources\PushNotifications;
use Devio\Pipedrive\Resources\Recents;
use Devio\Pipedrive\Resources\Roles;
use Devio\Pipedrive\Resources\SearchResults;
use Devio\Pipedrive\Resources\Stages;
use Devio\Pipedrive\Resources\UserConnections;
use Devio\Pipedrive\Resources\Users;
use Devio\Pipedrive\Resources\UserSettings;
use Devio\Pipedrive\Resources\Webhooks;
use Illuminate\Support\Str;
use Devio\Pipedrive\Http\Request;
use Devio\Pipedrive\Http\PipedriveClient;
use GuzzleHttp\Client as GuzzleClient;

/**
 * @method        Activities                activities()
 * @method        ActivityFields            activityFields()
 * @method        ActivityTypes             activityTypes()
 * @method        Authorizations            authorizations()
 * @method        CallLogs                  callLogs()
 * @method        Currencies                currencies()
 * @method        DealFields                dealFields()
 * @method        Deals                     deals()
 * @method        EmailMessages             emailMessages()
 * @method        EmailThreads              emailThreads()
 * @method        Files                     files()
 * @method        Filters                   filters()
 * @method        GlobalMessages            globalMessages()
 * @method        Goals                     goals()
 * @method        ItemSearch                itemSearch()
 * @method        Leads                     leads()
 * @method        NoteFields                noteFields()
 * @method        Notes                     notes()
 * @method        OrganizationFields        organizationFields()
 * @method        OrganizationRelationships organizationRelationships()
 * @method        Organizations             organizations()
 * @method        PermissionSets            permissionSets()
 * @method        PersonFields              personFields()
 * @method        Persons                   persons()
 * @method        Pipelines                 pipelines()
 * @method        ProductFields             productFields()
 * @method        Products                  products()
 * @method        PushNotifications         pushNotifications()
 * @method        Recents                   recents()
 * @method        Roles                     roles()
 * @method        SearchResults             searchResults()
 * @method        Stages                    stages()
 * @method        UserConnections           userConnections()
 * @method        Users                     users()
 * @method        UserSettings              userSettings()
 * @method        Webhooks                  webhooks()
 * @property-read Activities                $activities
 * @property-read ActivityFields            $activityFields
 * @property-read ActivityTypes             $activityTypes
 * @property-read Authorizations            $authorizations
 * @property-read CallLogs                  $callLogs
 * @property-read Currencies                $currencies
 * @property-read DealFields                $dealFields
 * @property-read Deals                     $deals
 * @property-read EmailMessages             $emailMessages
 * @property-read EmailThreads              $emailThreads
 * @property-read Files                     $files
 * @property-read Filters                   $filters
 * @property-read GlobalMessages            $globalMessages
 * @property-read Goals                     $goals
 * @property-read ItemSearch                $itemSearch
 * @property-read Leads                     $leads
 * @property-read NoteFields                $noteFields
 * @property-read Notes                     $notes
 * @property-read OrganizationFields        $organizationFields
 * @property-read OrganizationRelationships $organizationRelationships
 * @property-read Organizations             $organizations
 * @property-read PermissionSets            $permissionSets
 * @property-read PersonFields              $personFields
 * @property-read Persons                   $persons
 * @property-read Pipelines                 $pipelines
 * @property-read ProductFields             $productFields
 * @property-read Products                  $products
 * @property-read PushNotifications         $pushNotifications
 * @property-read Recents                   $recents
 * @property-read Roles                     $roles
 * @property-read SearchResults             $searchResults
 * @property-read Stages                    $stages
 * @property-read UserConnections           $userConnections
 * @property-read Users                     $users
 * @property-read UserSettings              $userSettings
 * @property-read Webhooks                  $webhooks
 */
class Pipedrive
{
    public const PIPEDRIVE_DOMAIN = 'pipedrive.com';
    public const API_VERSION = 'v1';
    public const PIPEDRIVE_API_DOMAIN = 'https://api.' . self::PIPEDRIVE_DOMAIN . '/';
    public const PIPEDRIVE_API_URL = self::PIPEDRIVE_API_DOMAIN . self::API_VERSION . '/';
    public const PIPEDRIVE_OAUTH_URL = 'https://oauth.' . self::PIPEDRIVE_DOMAIN . '/';

    /**
     * The base URI.
     *
     * @var string
     */
    protected string $baseURI;

    /**
     * The API token.
     *
     * @var string|PipedriveToken
     */
    protected string|PipedriveToken $token;

    /**
     * The guzzle version
     *
     * @var int
     */
    protected int $guzzleVersion;

    protected bool $isOauth;

    /**
     * The OAuth client id.
     *
     * @var string
     */
    protected string $clientId;

    /**
     * The client secret string.
     *
     * @var string
     */
    protected string $clientSecret;

    /**
     * The redirect URL.
     *
     * @var string
     */
    protected string $redirectUrl;

    /**
     * The OAuth storage.
     *
     * @var PipedriveTokenStorage
     */
    protected PipedriveTokenStorage $storage;

    public function isOauth(): bool
    {
        return $this->isOauth;
    }

    /**
     * Pipedrive constructor.
     *
     * @param string|PipedriveToken $token
     * @param string $uri
     * @param int    $guzzleVersion
     */
    public function __construct(
        string|PipedriveToken $token = '',
        string $uri = self::PIPEDRIVE_API_URL,
        int $guzzleVersion = 6
    ) {
        $this->token = $token;
        $this->baseURI = $uri;
        $this->guzzleVersion = $guzzleVersion;

        $this->isOauth = false;
    }

    /**
     * Prepare for OAuth.
     *
     * @param array $config
     *
     * @return Pipedrive
     */
    public static function OAuth(array $config): self
    {
        $guzzleVersion = $config['guzzleVersion'] ?? 6;

        $new = new self('oauth', self::PIPEDRIVE_API_DOMAIN, $guzzleVersion);

        $new->isOauth = true;

        $new->clientId = $config['clientId'];
        $new->clientSecret = $config['clientSecret'];
        $new->redirectUrl = $config['redirectUrl'];

        $new->storage = $config['storage'];

        return $new;
    }

    /**
     * Get the client ID.
     *
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * Get the client secret.
     *
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * Get the redirect URL.
     *
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    /**
     * Get the storage instance.
     *
     * @return PipedriveTokenStorage
     */
    public function getStorage(): PipedriveTokenStorage
    {
        return $this->storage;
    }

    /**
     * Redirect to OAuth.
     */
    public function OAuthRedirect()
    {
        $params = [
            'client_id'    => $this->clientId,
            'state'        => '',
            'redirect_uri' => $this->redirectUrl,
        ];
        $query = http_build_query($params);
        $url = self::PIPEDRIVE_OAUTH_URL . 'oauth/authorize?' . $query;
        header('Location: ' . $url);
        exit;
    }

    /**
     * Get the current OAuth access token object
     * (which includes refreshToken and expiresAt)
     */
    public function getAccessToken(): ?PipedriveToken
    {
        return $this->storage->getToken();
    }

    /**
     * OAuth authorization.
     *
     * @param $code
     * @return void
     *
     * @throws GuzzleException
     */
    public function authorize($code): void
    {
        $client = new GuzzleClient([
            'auth' => [
                $this->getClientId(),
                $this->getClientSecret()
            ]
        ]);
        $response = $client->request('POST', self::PIPEDRIVE_OAUTH_URL . 'oauth/token', [
            'form_params' => [
                'grant_type'   => 'authorization_code',
                'code'         => $code,
                'redirect_uri' => $this->redirectUrl,
            ]
        ]);
        $resBody = json_decode($response->getBody());

        $token = new PipedriveToken([
            'accessToken'  => $resBody->access_token,
            'expiresAt'    => time() + $resBody->expires_in,
            'refreshToken' => $resBody->refresh_token,
        ]);

        $this->storage->setToken($token);
    }

    /**
     * Get the resource instance.
     *
     * @param $resource
     * @return mixed
     */
    public function make($resource): mixed
    {
        $class = $this->resolveClassPath($resource);

        return new $class($this->getRequest());
    }

    /**
     * Get the resource path.
     *
     * @param $resource
     * @return string
     */
    protected function resolveClassPath($resource): string
    {
        return 'Devio\\Pipedrive\\Resources\\' . Str::studly($resource);
    }

    /**
     * Get the request instance.
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return new Request($this->getClient());
    }

    /**
     * Get the HTTP client instance.
     *
     * @return Client
     */
    protected function getClient(): Client
    {
        if ($this->guzzleVersion >= 6) {
            if ($this->isOauth()) {
                return PipedriveClient::OAuth($this->getBaseURI(), $this->storage, $this);
            }
            return new PipedriveClient($this->getBaseURI(), $this->token);
        } else {
            return new PipedriveClient4($this->getBaseURI(), $this->token);
        }
    }

    /**
     * Get the base URI.
     *
     * @return string
     */
    public function getBaseURI(): string
    {
        return $this->baseURI;
    }

    /**
     * Set the base URI.
     *
     * @param string $baseURI
     */
    public function setBaseURI(string $baseURI): void
    {
        $this->baseURI = $baseURI;
    }

    /**
     * Set the token.
     *
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * Any reading will return a resource.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->make($name);
    }

    /**
     * Methods will also return a resource.
     *
     * @param string $name
     * @param        $arguments
     *
     * @return mixed
     */
    public function __call(string $name, $arguments): mixed
    {
        if (! in_array($name, get_class_methods(get_class($this)))) {
            return $this->{$name};
        }
    }
}

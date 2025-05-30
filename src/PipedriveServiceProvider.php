<?php

namespace Devio\Pipedrive;

use Illuminate\Foundation\AliasLoader;
use Devio\Pipedrive\Exceptions\PipedriveException;
use Illuminate\Support\ServiceProvider;

class PipedriveServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Pipedrive', PipedriveFacade::class);
        });

        $this->app->singleton(Pipedrive::class, static function ($app) {
            $token = $app['config']->get('services.pipedrive.token');
            $uri = $app['config']->get('services.pipedrive.uri') ?: Pipedrive::PIPEDRIVE_API_URL;
            $guzzleVersion = $app['config']->get('services.pipedrive.guzzle_version') ?: 6;

            if (!$token) {
                throw new PipedriveException('Pipedrive was not configured in services.php configuration file.');
            }

            return new Pipedrive($token, $uri, $guzzleVersion);
        });

        $this->app->alias(Pipedrive::class, 'pipedrive');
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return ['pipedrive'];
    }
}

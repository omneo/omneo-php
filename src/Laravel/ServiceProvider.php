<?php

namespace Omneo\Laravel;

use Omneo\Client;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {

            list($domain, $token, $secret) = [
                config('services.omneo.domain'),
                config('services.omneo.token'),
                config('services.omneo.secret')
            ];

            if (! $domain || ! $token) {
                throw new \Exception('You must configure a domain and token to use the Omneo client');
            }

            $client = new Client($domain, $token);

            if ($secret) {
                $client->setSecret($secret);
            }

            return $client;

        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Client::class];
    }
}
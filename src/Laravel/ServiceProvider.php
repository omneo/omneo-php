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

            list($domain, $token) = [
                config('services.omneo.domain'),
                config('services.omneo.token')
            ];

            if (! $domain || ! $token) {
                throw new \Exception('You must configure a domain and token to use the Omneo client');
            }

            return new Client($domain, $token);

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
<?php

namespace Omneo\Modules;

use Omneo\Client;

abstract class Module
{
    /**
     * Omneo client.
     *
     * @var Client
     */
    protected $client;

    /**
     * AbstractModule constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
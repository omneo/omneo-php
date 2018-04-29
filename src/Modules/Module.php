<?php

namespace Omneo\Modules;

use Omneo\Client;
use Omneo\Concerns;

abstract class Module
{
    use Concerns\AppliesConstraint;

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
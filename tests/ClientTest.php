<?php

namespace Omneo;

use GuzzleHttp;

class ClientTest extends TestCase
{
    /**
     * @test
     */
    public function constructor_sets_domain_and_token()
    {
        $client = new Client('foo.omneo.io', 'batteryhorsestaple');

        $this->assertEquals('foo.omneo.io', $client->getDomain());
        $this->assertEquals('batteryhorsestaple', $client->getToken());
    }

    /**
     * @test
     */
    public function set_secret_sets_the_secret()
    {
        $client = new Client('foo.omneo.io', 'batteryhorsestaple');

        $client->setSecret('topsecret');

        $this->assertEquals('topsecret', $client->getSecret());
    }

    /**
     * @test
     */
    public function set_secret_is_chainable()
    {
        $client = new Client('foo.omneo.io', 'batteryhorsestaple');

        $chainable = $client->setSecret('topsecret');

        $this->assertInstanceOf(Client::class, $chainable);
    }

    /**
     * @test
     */
    public function middleware_sets_headers()
    {
        $container = [];

        $stack = GuzzleHttp\HandlerStack::create(new GuzzleHttp\Handler\MockHandler([
            new GuzzleHttp\Psr7\Response
        ]));

        $client = (new Client('foo.omneo.io', 'batteryhorsestaple'))
            ->setupClient($stack);

        // Make sure history middleware comes after setupClient as it needs to be called last
        $stack->push(GuzzleHttp\Middleware::history($container));

        $client->get('/');

        $this->assertEquals('application/json', $container[0]['request']->getHeaderLine('Accept'));
        $this->assertEquals('Bearer batteryhorsestaple', $container[0]['request']->getHeaderLine('Authorization'));
    }
}
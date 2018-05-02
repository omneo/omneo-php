<?php

namespace Omneo\Modules;

use Omneo;
use GuzzleHttp;
use \Mockery as m;

class WebhooksTest extends Omneo\TestCase
{
    /**
     * @test
     */
    public function can_access_module_from_client()
    {
        $client = new Omneo\Client('foo.omneo.io', 'batteryhorsestaple');

        $this->assertInstanceOf(Webhooks::class, $client->webhooks());
    }

    /**
     * @test
     */
    public function browse_returns_paginated_collection()
    {
        $module = new Webhooks(
            $client = m::mock(Omneo\Client::class)
        );

        $client
            ->shouldReceive('get')
            ->with('webhooks', [])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('webhooks/collection.json'))
            );

        $collection = $module->browse();

        $this->assertInstanceOf(Omneo\PaginatedCollection::class, $collection);
        $this->assertEquals(1, $collection->currentPage());
        $this->assertEquals(3, $collection->count());
        $this->assertEquals(1, $collection->lastPage());
        $this->assertEquals(3, $collection->total());
    }

    /**
     * @test
     */
    public function read_returns_webhook_entity()
    {
        $module = new Webhooks(
            $client = m::mock(Omneo\Client::class)
        );

        $client
            ->shouldReceive('get')
            ->with('webhooks/122')
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('webhooks/entity.json'))
            );

        $webhook = $module->read(122);

        $this->assertInstanceOf(Omneo\Webhook::Class, $webhook);
        $this->assertEquals('profile.created', $webhook->trigger);
        $this->assertEquals('https://foo.com/omneo', $webhook->url);
    }

    /**
     * @test
     */
    public function edit_returns_webhook_entity()
    {
        $module = new Webhooks(
            $client = m::mock(Omneo\Client::class)
        );

        $webhook = new Omneo\Webhook(
            $this->jsonStub('webhooks/entity.json')['data']
        );

        $webhook->trigger = 'profile.updated';

        $client
            ->shouldReceive('put')
            ->with('webhooks/122', [
                'json' => ['trigger' => 'profile.updated']
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('webhooks/entity.json'))
            );

        $webhook = $module->edit($webhook);

        $this->assertInstanceOf(Omneo\Webhook::Class, $webhook);
    }

    /**
     * @test
     */
    public function add_returns_webhook_entity()
    {
        $module = new Webhooks(
            $client = m::mock(Omneo\Client::class)
        );

        $webhook = new Omneo\Webhook(
            $this->jsonStub('webhooks/entity.json')['data']
        );

        $client
            ->shouldReceive('post')
            ->with('webhooks', [
                'json' => $webhook->toArray()
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('webhooks/entity.json'))
            );

        $webhook = $module->add($webhook);

        $this->assertInstanceOf(Omneo\Webhook::Class, $webhook);
    }

    /**
     * @test
     */
    public function delete_sends_delete_request()
    {
        $module = new Webhooks(
            $client = m::mock(Omneo\Client::class)
        );

        $webhook = new Omneo\Webhook(
            $this->jsonStub('webhooks/entity.json')['data']
        );

        $client
            ->shouldReceive('delete')
            ->with('webhooks/122')
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200)
            );

        $module->delete($webhook);
    }
}
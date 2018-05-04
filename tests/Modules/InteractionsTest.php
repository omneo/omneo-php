<?php

namespace Omneo\Modules;

use Omneo;
use GuzzleHttp;
use \Mockery as m;

class InteractionsTest extends Omneo\TestCase
{
    /**
     * @test
     */
    public function can_access_module_from_client()
    {
        $client = new Omneo\Client('foo.omneo.io', 'batteryhorsestaple');

        $this->assertInstanceOf(Interactions::class, $client->interactions());
    }

    /**
     * @test
     */
    public function browse_returns_paginated_collection()
    {
        $module = new Interactions(
            $client = m::mock(Omneo\Client::class)
        );

        $client
            ->shouldReceive('get')
            ->with('interactions', [])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('interactions/collection.json'))
            );

        $collection = $module->browse();

        $this->assertInstanceOf(Omneo\PaginatedCollection::class, $collection);
        $this->assertEquals(1, $collection->currentPage());
        $this->assertEquals(2, $collection->count());
        $this->assertEquals(2, $collection->lastPage());
        $this->assertEquals(4, $collection->total());
    }

    /**
     * @test
     */
    public function browse_paginated_collection_can_traverse()
    {
        $module = new Interactions(
            $client = m::mock(Omneo\Client::class)
        );

        $client
            ->shouldReceive('get')
            ->with('interactions', [])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('interactions/collection.json'))
            );

        $client
            ->shouldReceive('get')
            ->with('interactions', ['query' => ['page' => 2]])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('interactions/collection.json'))
            );

        $nextPage = $module->browse()->next();

        $this->assertInstanceOf(Omneo\PaginatedCollection::class, $nextPage);
    }

    /**
     * @test
     */
    public function browse_accepts_constraint()
    {
        $module = new Interactions(
            $client = m::mock(Omneo\Client::class)
        );

        $client
            ->shouldReceive('get')
            ->with('interactions', [
                'query' => [
                    'filter' => [
                        'channel' => [
                            'eq' => 'support'
                        ]
                    ]
                ]
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('interactions/collection.json'))
            );

        $module->browse(
            (new Omneo\Constraint)->where('channel', 'support')
        );
    }

    /**
     * @test
     */
    public function read_returns_interaction()
    {
        $module = new Interactions(
            $client = m::mock(Omneo\Client::class)
        );

        $client
            ->shouldReceive('get')
            ->with('interactions/2')
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('interactions/entity.json'))
            );

        $interaction = $module->read(2);

        $this->assertInstanceOf(Omneo\Interaction::Class, $interaction);
        $this->assertEquals('zendesk', $interaction->namespace);
        $this->assertEquals('Ticket created', $interaction->name);
    }

    /**
     * @test
     */
    public function edit_returns_interaction()
    {
        $module = new Interactions(
            $client = m::mock(Omneo\Client::class)
        );

        $interaction = new Omneo\Interaction(
            $this->jsonStub('interactions/entity.json')['data']
        );

        $interaction->signal = -1;

        $client
            ->shouldReceive('put')
            ->with('interactions/2', [
                'json' => ['signal' => -1]
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('interactions/entity.json'))
            );

        $interaction = $module->edit($interaction);

        $this->assertInstanceOf(Omneo\Interaction::Class, $interaction);
    }

    /**
     * @test
     */
    public function add_returns_interaction()
    {
        $module = new Interactions(
            $client = m::mock(Omneo\Client::class)
        );

        $interaction = new Omneo\Interaction(
            $this->jsonStub('interactions/entity.json')['data']
        );

        $client
            ->shouldReceive('post')
            ->with('interactions', [
                'json' => $interaction->toArray()
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('interactions/entity.json'))
            );

        $interaction = $module->add($interaction);

        $this->assertInstanceOf(Omneo\Interaction::Class, $interaction);
    }

    /**
     * @test
     */
    public function delete_sends_delete_request()
    {
        $module = new Interactions(
            $client = m::mock(Omneo\Client::class)
        );

        $interaction = new Omneo\Interaction(
            $this->jsonStub('interactions/entity.json')['data']
        );

        $client
            ->shouldReceive('delete')
            ->with('interactions/2')
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200)
            );

        $module->delete($interaction);
    }
}
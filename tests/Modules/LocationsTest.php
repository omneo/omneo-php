<?php

namespace Omneo\Modules;

use Omneo;
use GuzzleHttp;
use \Mockery as m;

class LocationsTest extends Omneo\TestCase
{
    /**
     * @test
     */
    public function can_access_module_from_client()
    {
        $client = new Omneo\Client('foo.omneo.io', 'batteryhorsestaple');

        $this->assertInstanceOf(Locations::class, $client->locations());
    }

    /**
     * @test
     */
    public function browse_returns_paginated_collection()
    {
        $module = new Locations(
            $client = m::mock(Omneo\Client::class)
        );

        $client
            ->shouldReceive('get')
            ->with('locations', [])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('locations/collection.json'))
            );

        $collection = $module->browse();

        $this->assertInstanceOf(Omneo\PaginatedCollection::class, $collection);
        $this->assertEquals(1, $collection->currentPage());
        $this->assertEquals(15, $collection->count());
        $this->assertEquals(4, $collection->lastPage());
        $this->assertEquals(47, $collection->total());
    }

    /**
     * @test
     */
    public function browse_paginated_collection_can_traverse()
    {
        $module = new Locations(
            $client = m::mock(Omneo\Client::class)
        );

        $client
            ->shouldReceive('get')
            ->with('locations', [])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('locations/collection.json'))
            );

        $client
            ->shouldReceive('get')
            ->with('locations', ['query' => ['page' => 2]])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('locations/collection.json'))
            );

        $nextPage = $module->browse()->next();

        $this->assertInstanceOf(Omneo\PaginatedCollection::class, $nextPage);
    }

    /**
     * @test
     */
    public function browse_accepts_constraint()
    {
        $module = new Locations(
            $client = m::mock(Omneo\Client::class)
        );

        $client
            ->shouldReceive('get')
            ->with('locations', [
                'query' => [
                    'filter' => [
                        'email' => [
                            'eq' => 'foo@example.com'
                        ]
                    ]
                ]
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('locations/collection.json'))
            );

        $module->browse(
            (new Omneo\Constraint)->where('email', 'foo@example.com')
        );
    }

    /**
     * @test
     */
    public function read_returns_location_entity()
    {
        $module = new Locations(
            $client = m::mock(Omneo\Client::class)
        );

        $client
            ->shouldReceive('get')
            ->with('locations/1')
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('locations/entity.json'))
            );

        $location = $module->read(1);

        $this->assertInstanceOf(Omneo\Location::Class, $location);
        $this->assertEquals('My Location2', $location->name);
        $this->assertEquals('My Location Description2', $location->description);
        $this->assertEquals('0400111002', $location->phone);
        $this->assertEquals('test2@omneo.io', $location->email);
    }

    /**
     * @test
     */
    public function edit_returns_profile_entity()
    {
        $module = new Locations(
            $client = m::mock(Omneo\Client::class)
        );

        $location = new Omneo\Location(
            $this->jsonStub('locations/entity.json')['data']
        );

        $location->email = 'test1@omneo.io';

        $client
            ->shouldReceive('put')
            ->with('locations/2', [
                'json' => ['email' => 'test1@omneo.io']
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('locations/entity.json'))
            );

        $location = $module->edit($location);

        $this->assertInstanceOf(Omneo\Location::Class, $location);
    }

    /**
     * @test
     */
    public function add_returns_profile_entity()
    {
        $module = new Locations(
            $client = m::mock(Omneo\Client::class)
        );

        $location = new Omneo\Location(
            $this->jsonStub('locations/entity.json')['data']
        );

        $client
            ->shouldReceive('post')
            ->with('locations', [
                'json' => $location->toArray()
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('locations/entity.json'))
            );

        $location = $module->add($location);

        $this->assertInstanceOf(Omneo\Location::Class, $location);
    }

    /**
     * @test
     */
    public function delete_sends_delete_request()
    {
        $module = new Locations(
            $client = m::mock(Omneo\Client::class)
        );

        $location = new Omneo\Location(
            $this->jsonStub('locations/entity.json')['data']
        );

        $client
            ->shouldReceive('delete')
            ->with('locations/2')
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200)
            );

        $module->delete($location);
    }
}
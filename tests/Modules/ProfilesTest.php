<?php

namespace Omneo\Modules;

use Omneo;
use GuzzleHttp;
use \Mockery as m;

class ProfilesTest extends Omneo\TestCase
{
    /**
     * @test
     */
    public function can_access_module_from_client()
    {
        $client = new Omneo\Client('foo.omneo.io', 'batteryhorsestaple');

        $this->assertInstanceOf(Profiles::class, $client->profiles());
    }

    /**
     * @test
     */
    public function browse_returns_paginated_collection()
    {
        $module = new Profiles(
            $client = m::mock(Omneo\Client::class)
        );

        $client
            ->shouldReceive('get')
            ->with('profiles', [])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('profiles/collection.json'))
            );

        $collection = $module->browse();

        $this->assertInstanceOf(Omneo\PaginatedCollection::class, $collection);
        $this->assertEquals(1, $collection->currentPage());
        $this->assertEquals(15, $collection->count());
        $this->assertEquals(3, $collection->lastPage());
        $this->assertEquals(35, $collection->total());
    }

    /**
     * @test
     */
    public function browse_paginated_collection_can_traverse()
    {
        $module = new Profiles(
            $client = m::mock(Omneo\Client::class)
        );

        $client
            ->shouldReceive('get')
            ->with('profiles', [])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('profiles/collection.json'))
            );

        $client
            ->shouldReceive('get')
            ->with('profiles', ['query' => ['page' => 2]])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('profiles/collection.json'))
            );

        $nextPage = $module->browse()->next();

        $this->assertInstanceOf(Omneo\PaginatedCollection::class, $nextPage);
    }

    /**
     * @test
     */
    public function browse_accepts_constraint()
    {
        $module = new Profiles(
            $client = m::mock(Omneo\Client::class)
        );

        $client
            ->shouldReceive('get')
            ->with('profiles', [
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
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('profiles/collection.json'))
            );

        $module->browse(
            (new Omneo\Constraint)->where('email', 'foo@example.com')
        );
    }

    /**
     * @test
     */
    public function read_returns_profile_entity()
    {
        $module = new Profiles(
            $client = m::mock(Omneo\Client::class)
        );

        $client
            ->shouldReceive('get')
            ->with('profiles/b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d')
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('profiles/entity.json'))
            );

        $profile = $module->read('b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d');

        $this->assertInstanceOf(Omneo\Profile::Class, $profile);
        $this->assertEquals('Carlos Bar', $profile->full_name);
        $this->assertEquals('mertz.blanca@yahoo.com', $profile->email);
    }

    /**
     * @test
     */
    public function edit_returns_profile_entity()
    {
        $module = new Profiles(
            $client = m::mock(Omneo\Client::class)
        );

        $profile = new Omneo\Profile(
            $this->jsonStub('profiles/entity.json')['data']
        );

        $profile->email = 'profile1@example.com';

        $client
            ->shouldReceive('put')
            ->with('profiles/b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d', [
                'json' => ['email' => 'profile1@example.com']
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('profiles/entity.json'))
            );

        $profile = $module->edit($profile);

        $this->assertInstanceOf(Omneo\Profile::Class, $profile);
    }

    /**
     * @test
     */
    public function add_returns_profile_entity()
    {
        $module = new Profiles(
            $client = m::mock(Omneo\Client::class)
        );

        $profile = new Omneo\Profile(
            $this->jsonStub('profiles/entity.json')['data']
        );

        $client
            ->shouldReceive('post')
            ->with('profiles', [
                'json' => $profile->toArray()
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('profiles/entity.json'))
            );

        $profile = $module->add($profile);

        $this->assertInstanceOf(Omneo\Profile::Class, $profile);
    }

    /**
     * @test
     */
    public function delete_sends_delete_request()
    {
        $module = new Profiles(
            $client = m::mock(Omneo\Client::class)
        );

        $profile = new Omneo\Profile(
            $this->jsonStub('profiles/entity.json')['data']
        );

        $client
            ->shouldReceive('delete')
            ->with('profiles/b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d')
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200)
            );

        $module->delete($profile);
    }
}
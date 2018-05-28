<?php

namespace Omneo\Modules;

use Illuminate\Support\Collection;
use Omneo;
use GuzzleHttp;
use \Mockery as m;

class IdentitiesTest extends Omneo\TestCase
{
    /**
     * @test
     */
    public function can_access_module_from_client_with_profile()
    {
        $client = new Omneo\Client('foo.omneo.io', 'batteryhorsestaple');

        $module = $client->identities(new Omneo\Profile(['id' => 'b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d']));

        $this->assertInstanceOf(Identities::class, $module);
    }

    /**
     * @test
     */
    public function browse_returns_collection_of_identities()
    {
        $module = new Identities(
            $client = m::mock(Omneo\Client::class),
            new Omneo\Profile(['id' => 'b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d'])
        );

        $client
            ->shouldReceive('get')
            ->with('profiles/b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d/identities')
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('identities/collection.json'))
            );

        $collection = $module->browse();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertInstanceOf(Omneo\Identity::class, $collection->first());
        $this->assertEquals(2, $collection->count());
    }

    /**
     * @test
     */
    public function read_returns_identity()
    {
        $module = new Identities(
            $client = m::mock(Omneo\Client::class),
            new Omneo\Profile(['id' => 'b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d'])
        );

        $client
            ->shouldReceive('get')
            ->with('profiles/b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d/identities/zendesk')
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('identities/entity.json'))
            );

        $identity = $module->read('zendesk');

        $this->assertInstanceOf(Omneo\Identity::Class, $identity);
        $this->assertEquals('zendesk', $identity->handle);
        $this->assertEquals('123', $identity->identifier);
    }

    /**
     * @test
     */
    public function edit_returns_identity()
    {
        $module = new Identities(
            $client = m::mock(Omneo\Client::class),
            new Omneo\Profile(['id' => 'b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d'])
        );

        $identity = new Omneo\Identity(
            $this->jsonStub('identities/entity.json')['data']
        );

        $identity->identifier = 'abc';

        $client
            ->shouldReceive('put')
            ->with('profiles/b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d/identities/zendesk', [
                'json' => ['identifier' => 'abc']
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('identities/entity.json'))
            );

        $identity = $module->edit($identity);

        $this->assertInstanceOf(Omneo\Identity::Class, $identity);
    }

    /**
     * @test
     */
    public function add_returns_identity()
    {
        $module = new Identities(
            $client = m::mock(Omneo\Client::class),
            new Omneo\Profile(['id' => 'b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d'])
        );

        $identity = new Omneo\Identity(
            $this->jsonStub('identities/entity.json')['data']
        );

        $client
            ->shouldReceive('post')
            ->with('profiles/b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d/identities', [
                'json' => $identity->toArray()
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('identities/entity.json'))
            );

        $identity = $module->add($identity);

        $this->assertInstanceOf(Omneo\Identity::Class, $identity);
    }

    /**
     * @test
     */
    public function edit_or_add_edits_when_existing()
    {
        $module = new Identities(
            $client = m::mock(Omneo\Client::class),
            new Omneo\Profile(['id' => 'b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d'])
        );

        $identity = new Omneo\Identity(
            $this->jsonStub('identities/entity.json')['data']
        );

        $client
            ->shouldReceive('put')
            ->with('profiles/b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d/identities/zendesk', [
                'json' => ['identifier' => '123']
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('identities/entity.json'))
            );

        $identity = $module->editOrAdd($identity->setDirtyAttributes('identifier'));

        $this->assertInstanceOf(Omneo\Identity::Class, $identity);
    }

    /**
     * @test
     */
    public function edit_or_add_adds_when_missing()
    {
        $module = new Identities(
            $client = m::mock(Omneo\Client::class),
            new Omneo\Profile(['id' => 'b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d'])
        );

        $identity = new Omneo\Identity(
            $this->jsonStub('identities/entity.json')['data']
        );

        $client
            ->shouldReceive('put')
            ->with('profiles/b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d/identities/zendesk', [
                'json' => ['identifier' => '123']
            ])
            ->once()
            ->andThrow(new GuzzleHttp\Exception\ClientException(
                'Not Found',
                new GuzzleHttp\Psr7\Request('PUT', 'profiles/b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d/identities/zendesk'),
                new GuzzleHttp\Psr7\Response(404)
            ));

        $client
            ->shouldReceive('post')
            ->with('profiles/b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d/identities', [
                'json' => $identity->toArray()
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('identities/entity.json'))
            );

        $identity = $module->editOrAdd($identity->setDirtyAttributes('identifier'));

        $this->assertInstanceOf(Omneo\Identity::Class, $identity);
    }

    /**
     * @test
     */
    public function delete_sends_delete_request()
    {
        $module = new Identities(
            $client = m::mock(Omneo\Client::class),
            new Omneo\Profile(['id' => 'b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d'])
        );

        $identity = new Omneo\Identity(
            $this->jsonStub('identities/entity.json')['data']
        );

        $client
            ->shouldReceive('delete')
            ->with('profiles/b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d/identities/zendesk')
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200)
            );

        $module->delete($identity);
    }
}
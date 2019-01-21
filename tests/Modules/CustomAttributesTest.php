<?php

namespace Omneo\Modules;

use Illuminate\Support\Collection;
use Omneo;
use GuzzleHttp;
use \Mockery as m;

class CustomAttributesTest extends Omneo\TestCase
{
    /**
     * @test
     */
    public function can_access_module_from_client_with_profile()
    {
        $client = new Omneo\Client('foo.omneo.io', 'batteryhorsestaple');

        $module = $client->customAttributes(new Omneo\Profile(['id' => 'b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d']));

        $this->assertInstanceOf(CustomAttributes::class, $module);
    }

    /**
     * @test
     */
    public function browse_returns_collection_of_custom_attributes()
    {
        $module = new CustomAttributes(
            $client = m::mock(Omneo\Client::class),
            new Omneo\Profile(['id' => 'b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d'])
        );

        $client
            ->shouldReceive('get')
            ->with('profiles/b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d/attributes/custom')
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('customAttributes/collection.json'))
            );

        $collection = $module->browse();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertInstanceOf(Omneo\CustomAttribute::class, $collection->first());
        $this->assertEquals(2, $collection->count());
    }

    /**
     * @test
     */
    public function read_returns_custom_attribute()
    {
        $module = new CustomAttributes(
            $client = m::mock(Omneo\Client::class),
            new Omneo\Profile(['id' => 'b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d'])
        );

        $client
            ->shouldReceive('get')
            ->with('profiles/b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d/attributes/custom/comms:interests')
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('customAttributes/entity.json'))
            );

        $customAttribute = $module->read('comms:interests');

        $this->assertInstanceOf(Omneo\CustomAttribute::Class, $customAttribute);
        $this->assertEquals('comms', $customAttribute->namespace);
        $this->assertEquals('interests', $customAttribute->handle);
    }

    /**
     * @test
     */
    public function edit_returns_custom_attribute()
    {
        $module = new CustomAttributes(
            $client = m::mock(Omneo\Client::class),
            new Omneo\Profile(['id' => 'b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d'])
        );

        $customAttribute = new Omneo\CustomAttribute(
            $this->jsonStub('customAttributes/entity.json')['data']
        );

        $customAttribute->value = 'abc';

        $client
            ->shouldReceive('put')
            ->with('profiles/b1d6f3ec-38cd-4747-b7f3-6649c9c05c5d/attributes/custom/comms:interests', [
                'json' => ['value' => 'abc']
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('customAttributes/entity.json'))
            );

        $customAttribute = $module->edit($customAttribute);

        $this->assertInstanceOf(Omneo\CustomAttribute::Class, $customAttribute);
    }
}
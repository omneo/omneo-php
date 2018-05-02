<?php

namespace Omneo\Modules;

use Illuminate\Support\Collection;
use Omneo;
use GuzzleHttp;
use \Mockery as m;

class CustomFieldsTest extends Omneo\TestCase
{
    /**
     * @test
     */
    public function can_access_module_from_client()
    {
        $client = new Omneo\Client('foo.omneo.io', 'batteryhorsestaple');

        $this->assertInstanceOf(CustomFields::class, $client->customFields());
    }

    /**
     * @test
     */
    public function browse_with_tenant_returns_collection()
    {
        $module = new CustomFields(
            $client = m::mock(Omneo\Client::class),
            new Omneo\Tenant
        );

        $client
            ->shouldReceive('get')
            ->with('tenants/custom-fields', [])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('custom_fields/collection.json'))
            );

        $collection = $module->browse();

        $this->assertInstanceOf(Collection::class, $collection);

        $this->assertInstanceOf(Omneo\CustomField::class, $collection->first());
    }

    /**
     * @test
     */
    public function read_returns_custom_field_entity()
    {
        $module = new CustomFields(
            $client = m::mock(Omneo\Client::class),
            new Omneo\Tenant
        );

        $client
            ->shouldReceive('get')
            ->with('tenants/custom-fields/zendesk:secret')
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('custom_fields/entity.json'))
            );

        $customField = $module->read('zendesk', 'secret');

        $this->assertInstanceOf(Omneo\CustomField::Class, $customField);
        $this->assertEquals('zendesk', $customField->namespace);
        $this->assertEquals('secret', $customField->handle);
        $this->assertEquals('foobar', $customField->value);
    }

    /**
     * @test
     */
    public function edit_returns_custom_field_entity()
    {
        $module = new CustomFields(
            $client = m::mock(Omneo\Client::class),
            new Omneo\Tenant
        );

        $customField = new Omneo\CustomField(
            $this->jsonStub('custom_fields/entity.json')['data']
        );

        $customField->value = 'batteryhorsestaple';

        $client
            ->shouldReceive('put')
            ->with('tenants/custom-fields/zendesk:secret', [
                'json' => ['value' => 'batteryhorsestaple']
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('custom_fields/entity.json'))
            );

        $customField = $module->edit($customField);

        $this->assertInstanceOf(Omneo\CustomField::Class, $customField);
    }

    /**
     * @test
     */
    public function add_returns_profile_entity()
    {
        $module = new CustomFields(
            $client = m::mock(Omneo\Client::class),
            new Omneo\Tenant
        );

        $customField = new Omneo\CustomField(
            $this->jsonStub('custom_fields/entity.json')['data']
        );

        $client
            ->shouldReceive('post')
            ->with('tenants/custom-fields', [
                'json' => $customField->toArray()
            ])
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200, [], $this->stub('custom_fields/entity.json'))
            );

        $customField = $module->add($customField);

        $this->assertInstanceOf(Omneo\CustomField::Class, $customField);
    }

    /**
     * @test
     */
    public function delete_sends_delete_request()
    {
        $module = new CustomFields(
            $client = m::mock(Omneo\Client::class),
            new Omneo\Tenant
        );

        $customField = new Omneo\CustomField(
            $this->jsonStub('custom_fields/entity.json')['data']
        );

        $client
            ->shouldReceive('delete')
            ->with('tenants/custom-fields/zendesk:secret')
            ->once()
            ->andReturn(
                new GuzzleHttp\Psr7\Response(200)
            );

        $module->delete($customField);
    }
}
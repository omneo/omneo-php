<?php

namespace Omneo\Modules;

use Omneo;
use Illuminate\Support\Fluent;
use Illuminate\Support\Collection;

class ProfileTest extends Omneo\TestCase
{
    /**
     * @test
     */
    public function can_instantiate_and_access_attributes()
    {
        $profile = new Omneo\Profile(
            $this->jsonStub('profiles/entity.json')['data']
        );

        $this->assertInstanceOf(Omneo\Profile::class, $profile);

        $this->assertEquals('Carlos Bar', $profile->full_name);
        $this->assertEquals('mertz.blanca@yahoo.com', $profile->email);
        $this->assertEquals('0450 551 500', $profile->mobile_phone);
    }

    /**
     * @test
     */
    public function address_property_returns_address_entity()
    {
        $profile = new Omneo\Profile(
            $this->jsonStub('profiles/entity.json')['data']
        );

        $this->assertInstanceOf(Omneo\Address::class, $profile->address);

        $this->assertEquals('123 Foo bar', $profile->address->address_line_1);
        $this->assertEquals('Foo bar land', $profile->address->address_line_2);
        $this->assertEquals('FooBar inc.', $profile->address->company);
        $this->assertEquals('Melbourne', $profile->address->suburb);
        $this->assertEquals('3000', $profile->address->postcode);
    }

    /**
     * @test
     */
    public function identities_property_returns_collection_of_identities()
    {
        $profile = new Omneo\Profile(
            $this->jsonStub('profiles/entity.json')['data']
        );

        $this->assertInstanceOf(Collection::class, $profile->identities);
        $this->assertInstanceOf(Omneo\Identity::class, $profile->identities->first());

        $this->assertEquals('zendesk', $profile->identities->first()->handle);
        $this->assertEquals('XYZ', $profile->identities->first()->identifier);

        $this->assertEquals(['zendesk'], $profile->identities->keys()->toArray());
    }

    /**
     * @test
     */
    public function attributes_property_returns_fluent()
    {
        $profile = new Omneo\Profile(
            $this->jsonStub('profiles/entity.json')['data']
        );

        $this->assertInstanceOf(Fluent::class, $profile->attributes);
        $this->assertEquals('bar', $profile->attributes->foo);

        $this->assertInstanceOf(Fluent::class, $profile->attributes->comms);
        $this->assertTrue($profile->attributes->comms->email_promo);
        $this->assertfalse($profile->attributes->comms->sms_promo);
    }
}
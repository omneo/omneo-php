<?php

namespace Omneo\Modules;

use Omneo;
use Illuminate\Support\Fluent;
use Illuminate\Support\Collection;

class LocationTest extends Omneo\TestCase
{
    /**
     * @test
     */
    public function can_instantiate_and_access_attributes()
    {
        $location = new Omneo\Location(
            $this->jsonStub('locations/entity.json')['data']
        );

        $this->assertInstanceOf(Omneo\Location::class, $location);

        $this->assertEquals('My Location2', $location->name);
        $this->assertEquals('My Location Description2', $location->description);
        $this->assertEquals('0400111002', $location->phone);
        $this->assertEquals('test2@omneo.io', $location->email);
    }

    /**
     * @test
     */
    public function address_property_returns_address_entity()
    {
        $location = new Omneo\Location(
            $this->jsonStub('locations/entity.json')['data']
        );

        $this->assertInstanceOf(Omneo\Address::class, $location->address);

        $this->assertEquals('address_line_12', $location->address->address_line_1);
        $this->assertEquals('address_line_22', $location->address->address_line_2);
        $this->assertEquals('address_line_32', $location->address->address_line_3);
        $this->assertEquals('Company2', $location->address->company);
        $this->assertEquals('Melbourne2', $location->address->city);
        $this->assertEquals('3002', $location->address->postcode);
        $this->assertEquals('TAS', $location->address->state);
        $this->assertEquals('AU', $location->address->country);
    }

}
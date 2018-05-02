<?php

namespace Omneo\Modules;

use Omneo;

class CustomFieldTest extends Omneo\TestCase
{
    /**
     * @test
     */
    public function can_instantiate_and_access_attributes()
    {
        $customField = new Omneo\CustomField(
            $this->jsonStub('custom_fields/entity.json')['data']
        );

        $this->assertInstanceOf(Omneo\CustomField::class, $customField);

        $this->assertEquals('zendesk', $customField->namespace);
        $this->assertEquals('secret', $customField->handle);
        $this->assertEquals('foobar', $customField->value);
    }
}
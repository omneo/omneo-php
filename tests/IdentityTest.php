<?php

namespace Omneo\Modules;

use Omneo;

class IdentityTest extends Omneo\TestCase
{
    /**
     * @test
     */
    public function can_instantiate_and_access_attributes()
    {
        $identity = new Omneo\Identity(
            $this->jsonStub('identities/entity.json')['data']
        );

        $this->assertInstanceOf(Omneo\Identity::class, $identity);

        $this->assertEquals('zendesk', $identity->handle);
        $this->assertEquals('123', $identity->identifier);
    }
}
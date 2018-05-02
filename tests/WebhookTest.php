<?php

namespace Omneo\Modules;

use Omneo;

class WebhookTest extends Omneo\TestCase
{
    /**
     * @test
     */
    public function can_instantiate_and_access_attributes()
    {
        $webhook = new Omneo\Webhook(
            $this->jsonStub('webhooks/entity.json')['data']
        );

        $this->assertInstanceOf(Omneo\Webhook::class, $webhook);

        $this->assertEquals('profile.created', $webhook->trigger);
        $this->assertEquals('https://foo.com/omneo', $webhook->url);
    }
}
<?php

namespace Omneo\Modules;

use Omneo\Webhook;
use Illuminate\Support\Collection;

class Webhooks extends Module
{
    /**
     * Fetch listing of webhooks attached to consumer.
     *
     * @return Collection|Webhook[]
     */
    public function browse()
    {
        $response = $this->client->get('webhooks');

        return (new Collection(
            json_decode((string) $response->getBody(), true)['data']
        ))->map(function (array $row) {
            return new Webhook($row);
        });
    }

    /**
     * Fetch a single webhook.
     *
     * @param  int $id
     * @return Webhook
     */
    public function read(int $id)
    {
        $response = $this->client->get(sprintf('webhooks/%d', $id));

        return new Webhook(
            json_decode((string) $response->getBody(), true)['data']
        );
    }

    /**
     * Edit the given webhook.
     *
     * @param  Webhook $webhook
     * @return Webhook
     * @throws \DomainException
     */
    public function edit(Webhook $webhook)
    {
        if (! $webhook->id) {
            throw new \DomainException('Webhook must contain an ID to edit');
        }

        $webhook->validate();

        $response = $this->client->put(sprintf('webhooks/%d', $webhook->id), [
            'json' => $webhook->toArray()
        ]);

        return new Webhook(
            json_decode((string) $response->getBody(), true)['data']
        );
    }

    /**
     * Create the given webhook.
     *
     * @param  Webhook $webhook
     * @return Webhook
     */
    public function add(Webhook $webhook)
    {
        $webhook->validate();

        $response = $this->client->post('webhooks', [
            'json' => $webhook->toArray()
        ]);

        return new Webhook(
            json_decode((string) $response->getBody(), true)['data']
        );
    }

    /**
     * Delete the given webhook.
     *
     * @param  Webhook $webhook
     * @return void
     */
    public function delete(Webhook $webhook)
    {
        if (! $webhook->id) {
            throw new \DomainException('Webhook must contain an ID to delete');
        }

        $this->client->delete(sprintf('webhooks/%d', $webhook->id));
    }
}
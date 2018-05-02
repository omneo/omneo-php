<?php

namespace Omneo\Modules;

use Omneo\Webhook;
use Omneo\Constraint;
use Omneo\PaginatedCollection;

class Webhooks extends Module
{
    /**
     * Fetch listing of webhooks.
     *
     * @param  Constraint  $constraint
     * @return PaginatedCollection|Webhook[]
     */
    public function browse(Constraint $constraint = null)
    {
        return $this->buildPaginatedCollection(
            $this->client->get('webhooks', $this->applyConstraint($constraint)),
            Webhook::class,
            [$this, __FUNCTION__],
            $constraint
        );
    }

    /**
     * Fetch a single webhook.
     *
     * @param  int  $id
     * @return Webhook
     */
    public function read(int $id)
    {
        return $this->buildEntity(
            $this->client->get(sprintf('webhooks/%d', $id)),
            Webhook::class
        );
    }

    /**
     * Edit the given webhook.
     *
     * @param  Webhook  $webhook
     * @return Webhook
     * @throws \DomainException
     */
    public function edit(Webhook $webhook)
    {
        if (! $webhook->id) {
            throw new \DomainException('Webhook must contain an ID to edit');
        }

        return $this->buildEntity(
            $this->client->put(sprintf('webhooks/%d', $webhook->id), [
                'json' => $webhook->getDirtyAttributeValues()
            ]),
            Webhook::class
        );
    }

    /**
     * Create the given webhook.
     *
     * @param  Webhook  $webhook
     * @return Webhook
     */
    public function add(Webhook $webhook)
    {
        return $this->buildEntity(
            $this->client->post('webhooks', [
                'json' => $webhook->toArray()
            ]),
            Webhook::class
        );
    }

    /**
     * Delete the given webhook.
     *
     * @param  Webhook  $webhook
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
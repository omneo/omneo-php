<?php

namespace Omneo\Modules;

use Omneo\Constraint;
use Omneo\Interaction;
use Omneo\PaginatedCollection;

class Interactions extends Module
{
    /**
     * Fetch paginated listing of interactions.
     *
     * @param  Constraint  $constraint
     * @return PaginatedCollection|Interaction[]
     */
    public function browse(Constraint $constraint = null)
    {
        return $this->buildPaginatedCollection(
            $this->client->get('interactions', $this->applyConstraint($constraint)),
            Interaction::class,
            [$this, __FUNCTION__],
            $constraint
        );
    }

    /**
     * Fetch a single interaction.
     *
     * @param  int  $id
     * @return Interaction
     */
    public function read(int $id)
    {
        return $this->buildEntity(
            $this->client->get(sprintf('interactions/%d', $id)),
            Interaction::class
        );
    }

    /**
     * Edit the given interaction.
     *
     * @param  Interaction  $interaction
     * @return Interaction
     * @throws \DomainException
     */
    public function edit(Interaction $interaction)
    {
        if (! $interaction->id) {
            throw new \DomainException('Interaction must contain an ID to edit');
        }

        return $this->buildEntity(
            $this->client->put(sprintf('interactions/%d', $interaction->id), [
                'json' => $interaction->getDirtyAttributeValues()
            ]),
            Interaction::class
        );
    }

    /**
     * Add the given interaction.
     *
     * @param  Interaction  $interaction
     * @return Interaction
     * @throws \DomainException
     */
    public function add(Interaction $interaction)
    {
        return $this->buildEntity(
            $this->client->post('interactions', [
                'json' => $interaction->toArray()
            ]),
            Interaction::class
        );
    }

    /**
     * Delete the given interaction.
     *
     * @param  Interaction  $interaction
     * @return void
     */
    public function delete(Interaction $interaction)
    {
        if (! $interaction->id) {
            throw new \DomainException('Interaction must contain an ID to delete');
        }

        $this->client->delete(sprintf('interactions/%d', $interaction->id));
    }
}
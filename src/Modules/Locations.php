<?php

namespace Omneo\Modules;

use Omneo\Location;
use Omneo\Constraint;
use Omneo\PaginatedCollection;

class Locations extends Module
{
    /**
     * Fetch listing of profiles.
     *
     * @param  Constraint  $constraint
     * @return PaginatedCollection|Location[]
     */
    public function browse(Constraint $constraint = null)
    {
        return $this->buildPaginatedCollection(
            $this->client->get('locations', $this->applyConstraint($constraint)),
            Location::class,
            [$this, __FUNCTION__],
            $constraint
        );
    }

    /**
     * Fetch a single profile.
     *
     * @param  int  $id
     * @return Location
     */
    public function read(int $id)
    {
        return $this->buildEntity(
            $this->client->get(sprintf('locations/%d', $id)),
            Location::class
        );
    }

    /**
     * Edit the given profile.
     *
     * @param  Location  $location
     * @return Location
     * @throws \DomainException
     */
    public function edit(Location $location)
    {
        if (! $location->id) {
            throw new \DomainException('Location must contain an ID to edit');
        }

        return $this->buildEntity(
            $this->client->put(sprintf('locations/%d', $location->id), [
                'json' => $location->getDirtyAttributeValues()
            ]),
            Location::class
        );
    }

    /**
     * Create the given profile.
     *
     * @param  Location  $location
     * @return Location
     */
    public function add(Location $location)
    {
        return $this->buildEntity(
            $this->client->post('locations', [
                'json' => $location->toArray()
            ]),
            Location::class
        );
    }

    /**
     * Delete the given profile.
     *
     * @param  Location  $location
     * @return void
     */
    public function delete(Location $location)
    {
        if (! $location->id) {
            throw new \DomainException('Location must contain an ID to delete');
        }

        $this->client->delete(sprintf('locations/%d', $location->id));
    }
}
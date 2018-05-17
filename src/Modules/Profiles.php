<?php

namespace Omneo\Modules;

use Omneo\Profile;
use Omneo\Constraint;
use Omneo\PaginatedCollection;

class Profiles extends Module
{
    /**
     * Fetch listing of profiles.
     *
     * @param  Constraint  $constraint
     * @return PaginatedCollection|Profile[]
     */
    public function browse(Constraint $constraint = null)
    {
        return $this->buildPaginatedCollection(
            $this->client->get('profiles', $this->applyConstraint($constraint)),
            Profile::class,
            [$this, __FUNCTION__],
            $constraint
        );
    }

    /**
     * Fetch a single profile.
     *
     * @param  string  $id
     * @return Profile
     */
    public function read(string $id)
    {
        return $this->buildEntity(
            $this->client->get(sprintf('profiles/%s', $id)),
            Profile::class
        );
    }

    /**
     * Edit the given profile.
     *
     * @param  Profile  $profile
     * @return Profile
     * @throws \DomainException
     */
    public function edit(Profile $profile)
    {
        if (! $profile->id) {
            throw new \DomainException('Profile must contain an ID to edit');
        }

        return $this->buildEntity(
            $this->client->put(sprintf('profiles/%s', $profile->id), [
                'json' => $profile->getDirtyAttributeValues()
            ]),
            Profile::class
        );
    }

    /**
     * Create the given profile.
     *
     * @param  Profile  $profile
     * @return Profile
     */
    public function add(Profile $profile)
    {
        return $this->buildEntity(
            $this->client->post('profiles', [
                'json' => $profile->toArray()
            ]),
            Profile::class
        );
    }

    /**
     * Delete the given profile.
     *
     * @param  Profile  $profile
     * @return void
     */
    public function delete(Profile $profile)
    {
        if (! $profile->id) {
            throw new \DomainException('Profile must contain an ID to delete');
        }

        $this->client->delete(sprintf('profiles/%s', $profile->id));
    }
}
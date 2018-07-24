<?php

namespace Omneo\Modules;

use Omneo\Profile;
use Omneo\Constraint;
use Omneo\PaginatedCollection;
use Illuminate\Support\Collection;

class Profiles extends Module
{
    /**
     * The maximum batch size.
     *
     * @const
     */
    const MAX_BATCH_SIZE = 500;

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
     * Batch create a collection of profiles.
     *
     * @param  Collection|Profile[] $profiles
     * @param  string $matchCriteria
     * @param  int  $size
     * @return void
     */
    public function batch(Collection $profiles, string $matchCriteria = 'email', int $size = self::MAX_BATCH_SIZE)
    {
        if ($size > self::MAX_BATCH_SIZE) {
            $size = self::MAX_BATCH_SIZE;
        }

        $profiles->chunk($size)->each(function(Collection $chunk) use ($matchCriteria) {
            $this->client->post('profiles/batch', [
                'json' => [
                    'match_criteria' => $matchCriteria,
                    'profiles' => $chunk->toArray(),
                ]
            ]);
        });
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

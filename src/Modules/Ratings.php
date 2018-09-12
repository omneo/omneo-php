<?php

namespace Omneo\Modules;

use Omneo\Rating;
use Omneo\Constraint;
use Omneo\PaginatedCollection;

class Ratings extends Module
{
    /**
     * Fetch paginated listing of ratings.
     *
     * @param  Constraint $constraint
     * @return PaginatedCollection|Rating[]
     */
    public function browse(Constraint $constraint = null)
    {
        return $this->buildPaginatedCollection(
            $this->client->get('ratings', $this->applyConstraint($constraint)),
            Rating::class,
            [$this, __FUNCTION__],
            $constraint
        );
    }

    /**
     * Fetch a single rating.
     *
     * @param  int $id
     * @return Rating
     */
    public function read(int $id)
    {
        return $this->buildEntity(
            $this->client->get(sprintf('ratings/%d', $id)),
            Rating::class
        );
    }

    /**
     * Edit the given ratings.
     *
     * @param  Rating $rating
     * @return Rating
     * @throws \DomainException
     */
    public function edit(Rating $rating)
    {
        if (! $rating->id) {
            throw new \DomainException('Rating must contain an ID to edit');
        }

        return $this->buildEntity(
            $this->client->put(sprintf('ratings/%d', $rating->id), [
                'json' => $rating->getDirtyAttributeValues()
            ]),
            Rating::class
        );
    }

    /**
     * Add the given rating.
     *
     * @param  Rating $rating
     * @return Rating
     * @throws \DomainException
     */
    public function add(Rating $rating)
    {
        return $this->buildEntity(
            $this->client->post('ratings', [
                'json' => $rating->toArray()
            ]),
            Rating::class
        );
    }

    /**
     * Delete the given ratings.
     *
     * @param  Rating $rating
     * @return void
     */
    public function delete(Rating $rating)
    {
        if (! $rating->id) {
            throw new \DomainException('Rating must contain an ID to delete');
        }

        $this->client->delete(sprintf('ratings/%d', $rating->id));
    }
}

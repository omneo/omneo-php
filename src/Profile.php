<?php

namespace Omneo;

use Illuminate\Support\Collection;

class Profile extends Entity implements Contracts\HasUri
{
    /**
     * Return URI for this entity.
     *
     * @return string
     */
    public function uri()
    {
        return sprintf('profiles/%d', $this->id);
    }

    /**
     * Return address entity.
     *
     * @param  array  $attribute
     * @return Address
     */
    public function getAddressAttribute($attribute)
    {
        return new Address($attribute);
    }

    /**
     * Return identities collection.
     *
     * @param  array  $attribute
     * @return Collection|Identity[]
     */
    public function getIdentitiesAttribute($attribute)
    {
        return (new Collection((array) $attribute))
            ->map(function (array $identity) {
                return new Identity($identity);
            });
    }
}

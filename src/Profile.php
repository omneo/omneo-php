<?php

namespace Omneo;

use Illuminate\Support\Fluent;
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
        return sprintf('profiles/%s', $this->id);
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
            })->keyBy('handle');
    }

    /**
     * Return attributes fluent.
     *
     * @param  array  $attribute
     * @return Fluent
     */
    public function getAttributesAttribute($attribute)
    {
        $fluent = new Fluent;

        foreach ($attribute as $key => $value) {

            if (is_array($value)) {
                $fluent->{$key} = new Fluent($value);
                continue;
            }

            $fluent->{$key} = $value;

        }

        return $fluent;
    }
}

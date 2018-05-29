<?php

namespace Omneo;

use Illuminate\Support\Fluent;
use Illuminate\Support\Collection;

class Location extends Entity implements Contracts\HasUri
{
    /**
     * Return URI for this entity.
     *
     * @return string
     */
    public function uri()
    {
        return sprintf('locations/%d', $this->id);
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
}

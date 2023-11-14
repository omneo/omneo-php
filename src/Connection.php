<?php

namespace Omneo;

use Illuminate\Support\Collection;

class Connection extends Entity implements Contracts\HasUri
{
    /**
     * Return URI for this entity.
     *
     * @return string
     */
    public function uri()
    {
        return sprintf('connections/%s', $this->id);
    }
}

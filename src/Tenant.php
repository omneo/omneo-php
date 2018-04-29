<?php

namespace Omneo;

class Tenant extends Entity implements Contracts\HasUri
{
    /**
     * Return URI for this entity.
     *
     * @return string
     */
    public function uri()
    {
        return 'tenants';
    }
}

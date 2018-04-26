<?php

namespace Omneo\Contracts;

interface HasUri
{
    /**
     * Return URI for this entity.
     *
     * @return string
     */
    public function uri();
}
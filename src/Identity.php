<?php

namespace Omneo;

class Identity extends Entity
{
    /**
     * Cast identity to a string.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->identifier;
    }
}

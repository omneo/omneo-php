<?php

namespace Omneo;

class Redemption extends Entity implements Contracts\HasUri
{
    /**
     * Return URI for this entity.
     *
     * @return string
     */
    public function uri()
    {
        return sprintf('redemptions/%d', $this->id);
    }
}

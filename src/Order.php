<?php

namespace Omneo;

class Order extends Entity implements Contracts\HasUri
{
    /**
     * Return URI for this entity.
     *
     * @return string
     */
    public function uri()
    {
        return sprintf('orders/%s', $this->id);
    }
}

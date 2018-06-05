<?php

namespace Omneo;

use Illuminate\Support\Collection;

class Product extends Entity implements Contracts\HasUri
{
    /**
     * Return URI for this entity.
     *
     * @return string
     */
    public function uri()
    {
        return sprintf('products/%s', $this->id);
    }

    /**
     * Get the variants.
     *
     * @param array $variants
     * @return Collection|ProductVariant[]
     */
    public function getVariantsAttribute(array $variants)
    {
        return collect($variants)->mapInto(ProductVariant::class);
    }
}

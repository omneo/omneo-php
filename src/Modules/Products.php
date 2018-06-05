<?php

namespace Omneo\Modules;

use Omneo\Product;
use Omneo\Constraint;
use Omneo\PaginatedCollection;

class Products extends Module
{
    /**
     * Fetch listing of products.
     *
     * @param  Constraint  $constraint
     * @return PaginatedCollection|Product[]
     */
    public function browse(Constraint $constraint = null)
    {
        return $this->buildPaginatedCollection(
            $this->client->get('products', $this->applyConstraint($constraint)),
            Product::class,
            [$this, __FUNCTION__],
            $constraint
        );
    }

    /**
     * Fetch a single product.
     *
     * @param  string  $id
     * @return Product
     */
    public function read(string $id)
    {
        return $this->buildEntity(
            $this->client->get(sprintf('products/%s', $id)),
            Product::class
        );
    }

    /**
     * Edit the given product.
     *
     * @param  Product  $product
     * @return Product
     * @throws \DomainException
     */
    public function edit(Product $product)
    {
        if (! $product->id) {
            throw new \DomainException('Product must contain an ID to edit');
        }

        return $this->buildEntity(
            $this->client->put(sprintf('products/%s', $product->id), [
                'json' => $product->getDirtyAttributeValues()
            ]),
            Product::class
        );
    }

    /**
     * Create the given product.
     *
     * @param  Product  $product
     * @return Product
     */
    public function add(Product $product)
    {
        return $this->buildEntity(
            $this->client->post('products', [
                'json' => $product->toArray()
            ]),
            Products::class
        );
    }

    /**
     * Delete the given product.
     *
     * @param  Product  $product
     * @return void
     */
    public function delete(Product $product)
    {
        if (! $product->id) {
            throw new \DomainException('Product must contain an ID to delete');
        }

        $this->client->delete(sprintf('products/%s', $product->id));
    }
}

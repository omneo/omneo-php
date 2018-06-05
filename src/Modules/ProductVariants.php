<?php

namespace Omneo\Modules;

use Omneo\Client;
use Omneo\Contracts;
use Omneo\ProductVariant;
use Illuminate\Support\Collection;

class ProductVariants extends Module
{
    /**
     * Owner entity.
     *
     * @var Contracts\HasUri
     */
    protected $owner;

    /**
     * Product Variants constructor.
     *
     * @param  Client  $client
     * @param  Contracts\HasUri  $owner
     */
    public function __construct(Client $client, Contracts\HasUri $owner = null)
    {
        parent::__construct($client);

        $this->owner = $owner;
    }

    /**
     * Fetch listing of product variants.
     *
     * @return Collection|ProductVariant[]
     */
    public function browse()
    {
        return $this->buildCollection(
            $this->client->get(sprintf(
                '%s/%s',
                $this->owner->uri(),
                'variants'
            )),
            ProductVariant::class
        );
    }

    /**
     * Fetch a single product variant.
     *
     * @param  int  $id
     * @return ProductVariant
     */
    public function read(int $id)
    {
        return $this->buildEntity(
            $this->client->get(sprintf(
                '%s/%s/%s',
                $this->owner->uri(),
                'variants',
                $id
            )),
            ProductVariant::class
        );
    }

    /**
     * Edit the given product variant.
     *
     * @param  ProductVariant  $productVariant
     * @return ProductVariant
     * @throws \DomainException
     */
    public function edit(ProductVariant $productVariant)
    {
        if (! $productVariant->id) {
            throw new \DomainException('Product variant must contain an ID to edit');
        }

        return $this->buildEntity(
            $this->client->put(sprintf(
                '%s/%s/%s',
                $this->owner->uri(),
                'variants',
                $productVariant->id
            ), [
                'json' => $productVariant->getDirtyAttributeValues()
            ]),
            ProductVariant::class
        );
    }

    /**
     * Add the given product variant.
     *
     * @param  ProductVariant  $productVariant
     * @return ProductVariant
     */
    public function add(ProductVariant $productVariant)
    {
        return $this->buildEntity(
            $this->client->post(sprintf(
                '%s/%s',
                $this->owner->uri(),
                'variants'
            ), [
                'json' => $productVariant->toArray()
            ]),
            ProductVariant::class
        );
    }

    /**
     * Delete the given product variant.
     *
     * @param  ProductVariant  $productVariant
     * @return void
     */
    public function delete(ProductVariant $productVariant)
    {
        if (! $productVariant->id) {
            throw new \DomainException('Product variant must contain an ID to delete');
        }

        $this->client->delete(sprintf(
            '%s/%s/%s',
            $this->owner->uri(),
            'variants',
            $productVariant->id
        ));
    }
}

<?php

namespace Omneo\Modules;

use Omneo\Client;
use Omneo\Attribute;
use Omneo\Contracts;
use Illuminate\Support\Collection;

class Attributes extends Module
{
    /**
     * Owner entity.
     *
     * @var Contracts\HasUri
     */
    protected $owner;

    /**
     * Identities constructor.
     *
     * @param  Client  $client
     * @param  Contracts\HasUri  $owner
     */
    public function __construct(Client $client, Contracts\HasUri $owner)
    {
        parent::__construct($client);

        $this->owner = $owner;
    }

    /**
     * Fetch listing of identities.
     *
     * @return Collection|Attribute[]
     */
    public function browse()
    {
        return $this->buildCollection(
            $this->client->get(sprintf(
                '%s/%s',
                $this->owner->uri(),
                'attributes'
            )),
            Attribute::class
        );
    }

    /**
     * Fetch a single identity.
     *
     * @param  string  $handle
     * @return Attribute
     */
    public function read(string $handle)
    {
        return $this->buildEntity(
            $this->client->get(sprintf(
                '%s/%s/%s',
                $this->owner->uri(),
                'attributes',
                $handle
            )),
            Attribute::class
        );
    }

    /**
     * Edit the given identity.
     *
     * @param  Attribute  $attribute
     * @return Attribute
     * @throws \DomainException
     */
    public function edit(Attribute $attribute)
    {
        if (! $attribute->handle) {
            throw new \DomainException('Attribute must contain a handle to edit');
        }

        return $this->buildEntity(
            $this->client->put(sprintf(
                '%s/%s/%s',
                $this->owner->uri(),
                'attributes',
                $attribute->handle
            ), [
                'json' => $attribute->getDirtyAttributeValues()
            ]),
            Attribute::class
        );
    }
}
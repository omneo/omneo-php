<?php

namespace Omneo\Modules;

use Omneo\Client;
use Omneo\Attribute;
use Omneo\Contracts;
use Illuminate\Support\Collection;
use Omneo\CustomAttribute;

class CustomAttributes extends Module
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
     * @param  Client $client
     * @param  Contracts\HasUri $owner
     */
    public function __construct(Client $client, Contracts\HasUri $owner)
    {
        parent::__construct($client);

        $this->owner = $owner;
    }

    /**
     * Fetch listing of custom attributes.
     *
     * @return Collection|CustomAttribute[]
     */
    public function browse()
    {
        return $this->buildCollection(
            $this->client->get(sprintf(
                '%s/%s/%s',
                $this->owner->uri(),
                'attributes',
                'custom'
            )),
            CustomAttribute::class
        );
    }

    /**
     * Fetch a single custom attribute.
     *
     * @param  string $identifier
     * @return CustomAttribute
     */
    public function read(string $identifier)
    {
        if (count($identifiers = explode(':', $identifier)) < 2) {
            throw new \DomainException('The identifier must in the format of namespace:handle.');
        }

        return $this->buildEntity(
            $this->client->get(sprintf(
                '%s/%s/%s/%s',
                $this->owner->uri(),
                'attributes',
                'custom',
                $identifier
            )),
            CustomAttribute::class
        );
    }

    /**
     * Edit the given identity.
     *
     * @param  CustomAttribute $customAttribute
     * @return CustomAttribute
     * @throws \DomainException
     */
    public function edit(CustomAttribute $customAttribute)
    {
        if (! $customAttribute->namespace || ! $customAttribute->handle) {
            throw new \DomainException('Custom Attribute must have namespace and handle to update.');
        }

        return $this->buildEntity(
            $this->client->put(sprintf(
                '%s/%s/%s/%s',
                $this->owner->uri(),
                'attributes',
                'custom',
                $customAttribute->namespace . ':' . $customAttribute->handle
            ), [
                'json' => $customAttribute->getDirtyAttributeValues()
            ]),
            CustomAttribute::class
        );
    }
}
<?php

namespace Omneo\Modules;

use Omneo\Client;
use Omneo\Constraint;
use Omneo\Identity;
use Omneo\Contracts;
use Illuminate\Support\Collection;
use GuzzleHttp\Exception\ClientException;

class Identities extends Module
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
    public function __construct(Client $client, Contracts\HasUri $owner = null)
    {
        parent::__construct($client);

        $this->owner = $owner;
    }

    /**
     * Fetch a listing of root identities.
     *
     * @param Constraint $constraint
     * @return \Omneo\PaginatedCollection|Identity[]
     */
    public function search(Constraint $constraint = null)
    {
        return $this->buildPaginatedCollection(
            $this->client->get('identities', $this->applyConstraint($constraint)),
            Identity::class,
            [$this, __FUNCTION__],
            $constraint
        );
    }

    /**
     * Fetch listing of identities.
     *
     * @return Collection|Identity[]
     */
    public function browse()
    {
        return $this->buildCollection(
            $this->client->get(sprintf(
                '%s/%s',
                $this->owner->uri(),
                'identities'
            )),
            Identity::class
        );
    }

    /**
     * Fetch a single identity by handle.
     *
     * @param  string $handle
     * @return Identity
     */
    public function read(string $handle)
    {
        return $this->buildEntity(
            $this->client->get(sprintf(
                '%s/%s/%s',
                $this->owner->uri(),
                'identities',
                $handle
            )),
            Identity::class
        );
    }

    /**
     * Fetch a single identity by id.
     *
     * @param  Identity $identity
     * @return Identity
     */
    public function readById(Identity $identity)
    {
        if (! $identity->id) {
            throw new \DomainException('Identity must contain an id to read');
        }

        return $this->buildEntity(
            $this->client->get(sprintf(
                '%s/%s/%s/%s',
                $this->owner->uri(),
                'identities',
                'id',
                $identity->id
            )),
            Identity::class
        );
    }

    /**
     * Edit the given identity.
     *
     * @param  Identity $identity
     * @return Identity
     * @throws \DomainException
     */
    public function edit(Identity $identity)
    {
        if (! $identity->handle) {
            throw new \DomainException('Identity must contain a handle to edit');
        }

        return $this->buildEntity(
            $this->client->put(sprintf(
                '%s/%s/%s',
                $this->owner->uri(),
                'identities',
                $identity->handle
            ), [
                'json' => $identity->getDirtyAttributeValues()
            ]),
            Identity::class
        );
    }

    /**
     * Edit the given identity by id.
     *
     * @param  Identity $identity
     * @return Identity
     * @throws \DomainException
     */
    public function editById(Identity $identity)
    {
        if (! $identity->id) {
            throw new \DomainException('Identity must contain an id to edit');
        }

        return $this->buildEntity(
            $this->client->put(sprintf(
                '%s/%s/%s/%s',
                $this->owner->uri(),
                'identities',
                'id',
                $identity->id
            ), [
                'json' => $identity->getDirtyAttributeValues()
            ]),
            Identity::class
        );
    }

    /**
     * Add the given identity.
     *
     * @param  Identity $identity
     * @return Identity
     */
    public function add(Identity $identity)
    {
        return $this->buildEntity(
            $this->client->post(sprintf(
                '%s/%s',
                $this->owner->uri(),
                'identities'
            ), [
                'json' => $identity->toArray()
            ]),
            Identity::class
        );
    }

    /**
     * Edit or add the given identity.
     *
     * @param  Identity $identity
     * @return Identity
     * @throws ClientException
     */
    public function editOrAdd(Identity $identity)
    {
        try {
            return $this->edit($identity);
        } catch (ClientException $e) {

            if (404 === $e->getCode()) {
                return $this->add($identity);
            }

            throw $e;

        }
    }

    /**
     * Delete the given identity.
     *
     * @param  Identity $identity
     * @return void
     */
    public function delete(Identity $identity)
    {
        if (! $identity->handle) {
            throw new \DomainException('Identity must contain a handle to delete');
        }

        $this->client->delete(sprintf(
            '%s/%s/%s',
            $this->owner->uri(),
            'identities',
            $identity->handle
        ));
    }

    /**
     * Delete the given identity by id.
     *
     * @param  Identity $identity
     * @return void
     */
    public function deleteById(Identity $identity)
    {
        if (! $identity->id) {
            throw new \DomainException('Identity must contain an id to delete');
        }

        $this->client->delete(sprintf(
            '%s/%s/%s/%s',
            $this->owner->uri(),
            'identities',
            'id',
            $identity->id
        ));
    }
}

<?php

namespace Omneo\Modules;

use Omneo\Client;
use Omneo\Identity;
use Omneo\Contracts;
use Omneo\Constraint;
use Omneo\PaginatedCollection;
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
     * @param  Client  $client
     * @param  Contracts\HasUri  $owner
     */
    public function __construct(Client $client, Contracts\HasUri $owner = null)
    {
        parent::__construct($client);

        $this->owner = $owner;
    }

    /**
     * Fetch listing of identities.
     *
     * @param Constraint|null $constraint
     * @return Identity[]|PaginatedCollection
     */
    public function browse(Constraint $constraint = null)
    {
        return $this->buildPaginatedCollection(
            $this->client->get(
                $this->prepareUri(sprintf('%s/%s', optional($this->owner)->uri(), 'identities')),
                $this->applyConstraint($constraint)
            ),
            Identity::class,
            [$this, __FUNCTION__],
            $constraint
        );
    }

    /**
     * Fetch a single identity.
     *
     * @param  string  $handle
     * @return Identity
     */
    public function read(string $handle)
    {
        return $this->buildEntity(
            $this->client->get(
                $this->prepareUri(sprintf('%s/%s/%s', optional($this->owner)->uri(), 'identities', $handle))
            ),
            Identity::class
        );
    }

    /**
     * Edit the given identity.
     *
     * @param  Identity  $identity
     * @return Identity
     * @throws \DomainException
     */
    public function edit(Identity $identity)
    {
        if (! $identity->handle) {
            throw new \DomainException('Identity must contain a handle to edit');
        }

        $uri = $this->prepareUri(
            sprintf(
                '%s/%s/%s',
                optional($this->owner)->uri(),
                'identities',
                $identity->handle
            )
        );

        return $this->buildEntity(
            $this->client->put($uri, [
                'json' => $identity->getDirtyAttributeValues()
            ]),
            Identity::class
        );
    }

    /**
     * Add the given identity.
     *
     * @param  Identity  $identity
     * @return Identity
     */
    public function add(Identity $identity)
    {
        return $this->buildEntity(
            $this->client->post(
                $this->prepareUri(sprintf('%s/%s', optional($this->owner)->uri(), 'identities')),
                ['json' => $identity->toArray()]
            ),
            Identity::class
        );
    }

    /**
     * Edit or add the given identity.
     *
     * @param  Identity  $identity
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
     * @param  Identity  $identity
     * @return void
     */
    public function delete(Identity $identity)
    {
        if (! $identity->handle) {
            throw new \DomainException('Identity must contain a handle to delete');
        }

        $uri = $this->prepareUri(
            sprintf(
                '%s/%s/%s',
                $this->owner->uri(),
                'identities',
                $identity->handle
            )
        );

        $this->client->delete($uri);
    }

    /**
     * Prepare the URI.
     *
     * @param string $uri
     * @return string
     */
    protected function prepareUri(string $uri)
    {
        // Strip leading slashes. This can cause issues as a leading slash will remove
        // the base API path from the domain.
        return ltrim($uri, '/');
    }
}

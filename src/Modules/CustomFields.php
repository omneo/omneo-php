<?php

namespace Omneo\Modules;

use Omneo\Client;
use Omneo\Contracts;
use Omneo\CustomField;
use Illuminate\Support\Collection;

class CustomFields extends Module
{
    /**
     * Owner entity.
     *
     * @var Contracts\HasUri
     */
    protected $owner;

    /**
     * CustomFields constructor.
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
     * Fetch listing of custom fields attached to owner.
     *
     * @return Collection|CustomField[]
     */
    public function browse()
    {
        $response = $this->client->get(sprintf(
            '%s/%s',
            $this->owner->uri(),
            'custom-fields'
        ));

        return (new Collection(
            json_decode((string) $response->getBody(), true)['data']
        ))->map(function (array $row) {
            return new CustomField($row);
        });
    }

    /**
     * Read custom field with given namespace and handle.
     *
     * @param  string  $namespace
     * @param  string  $handle
     * @return CustomField
     */
    public function read(string $namespace, string $handle)
    {
        $response = $this->client->get(sprintf(
            '%s/%s/%s:%s',
            $this->owner->uri(),
            'custom-fields',
            $namespace,
            $handle
        ));

        return new CustomField(
            json_decode((string) $response->getBody(), true)['data']
        );
    }

    /**
     * Create or update given custom field.
     *
     * @param  CustomField  $customField
     * @return CustomField
     */
    public function edit(CustomField $customField)
    {
        dd($customField->validate());

        $response = $this->client->put(sprintf(
            '%s/%s/%s:%s',
            $this->owner->uri(),
            'custom-fields',
            $customField->namespace,
            $customField->handle
        ));

        return new CustomField(
            json_decode((string) $response->getBody(), true)['data']
        );
    }
}
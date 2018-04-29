<?php

namespace Omneo\Modules;

use Omneo\Client;
use Omneo\Contracts;
use Omneo\Constraint;
use Omneo\CustomField;
use Illuminate\Support\Collection;
use GuzzleHttp\Exception\ClientException;

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
     * @param  Constraint  $constraint
     * @return Collection|CustomField[]
     */
    public function browse(Constraint $constraint = null)
    {
        $response = $this->client->get(sprintf(
            '%s/%s',
            $this->owner->uri(),
            'custom-fields'
        ), [
            'query' => $this->applyConstraint($constraint)
        ]);

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
        $customField->validate();

        try {
            $response = $this->client->put(sprintf(
                '%s/%s/%s:%s',
                $this->owner->uri(),
                'custom-fields',
                $customField->namespace,
                $customField->handle
            ), [
                'json' => $customField->toArray()
            ]);
        } catch (ClientException $e) {

            if (404 === $e->getCode()) {
                return $this->add($customField);
            }

            throw $e;

        }

        return new CustomField(
            json_decode((string) $response->getBody(), true)['data']
        );
    }

    /**
     * Create the given custom field.
     *
     * @param  CustomField $customField
     * @return CustomField
     */
    public function add(CustomField $customField)
    {
        $customField->validate();

        $response = $this->client->post(sprintf(
            '%s/%s',
            $this->owner->uri(),
            'custom-fields'
        ), [
            'json' => $customField->toArray()
        ]);

        return new CustomField(
            json_decode((string) $response->getBody(), true)['data']
        );
    }

    /**
     * Delete custom field with given namespace and handle.
     *
     * @param  string  $namespace
     * @param  string  $handle
     * @return void
     */
    public function delete(string $namespace, string $handle)
    {
        $this->client->delete(sprintf(
            '%s/%s/%s:%s',
            $this->owner->uri(),
            'custom-fields',
            $namespace,
            $handle
        ));
    }
}
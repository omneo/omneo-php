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
        return $this->buildCollection(
            $this->client->get(sprintf(
                '%s/%s',
                $this->owner->uri(),
                'custom-fields'
            ), $this->applyConstraint($constraint)),
            CustomField::class
        );
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
        return $this->buildEntity(
            $this->client->get(sprintf(
                '%s/%s/%s:%s',
                $this->owner->uri(),
                'custom-fields',
                $namespace,
                $handle
            )),
            CustomField::class
        );
    }

    /**
     * Edit the given custom field.
     *
     * @param  CustomField  $customField
     * @return CustomField
     */
    public function edit(CustomField $customField)
    {
        return $this->buildEntity(
            $this->client->put(sprintf(
                '%s/%s/%s:%s',
                $this->owner->uri(),
                'custom-fields',
                $customField->namespace,
                $customField->handle
            ), [
                'json' => $customField->getDirtyAttributeValues()
            ]),
            CustomField::class
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
        return $this->buildEntity(
            $this->client->post(sprintf(
                '%s/%s',
                $this->owner->uri(),
                'custom-fields'
            ), [
                'json' => $customField->toArray()
            ]),
            CustomField::class
        );
    }

    /**
     * Create or update given custom field.
     *
     * @param  CustomField  $customField
     * @return CustomField
     */
    public function editOrAdd(CustomField $customField)
    {
        try {
            return $this->edit($customField);
        } catch (ClientException $e) {

            if (404 === $e->getCode()) {
                return $this->add($customField);
            }

            throw $e;

        }
    }

    /**
     * Delete custom field with given namespace and handle.
     *
     * @param  CustomField $customField
     * @return void
     */
    public function delete(CustomField $customField)
    {
        $this->client->delete(sprintf(
            '%s/%s/%s:%s',
            $this->owner->uri(),
            'custom-fields',
            $customField->namespace,
            $customField->handle
        ));
    }
}
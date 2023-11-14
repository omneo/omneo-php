<?php

namespace Omneo\Modules;

use Omneo\Connection;
use Omneo\Constraint;
use Omneo\PaginatedConnection;

class Connections extends Module
{
    /**
     * Fetch listing of connections.
     *
     * @param  Constraint  $constraint
     * @return PaginatedConnection|Connection[]
     */
    public function browse(Constraint $constraint = null)
    {
        return $this->buildPaginatedConnection(
            $this->client->get('connections', $this->applyConstraint($constraint)),
            Connection::class,
            [$this, __FUNCTION__],
            $constraint
        );
    }

    /**
     * Fetch a single connection.
     *
     * @param  string  $id
     * @return Connection
     */
    public function read(string $id)
    {
        return $this->buildEntity(
            $this->client->get(sprintf('connections/%s', $id)),
            Connection::class
        );
    }

    /**
     * Edit the given connection.
     *
     * @param  Connection  $connection
     * @return Connection
     * @throws \DomainException
     */
    public function edit(Connection $connection)
    {
        if (! $connection->id) {
            throw new \DomainException('Connection must contain an ID to edit');
        }

        return $this->buildEntity(
            $this->client->put(sprintf('connections/%s', $connection->id), [
                'json' => $connection->getDirtyAttributeValues()
            ]),
            Connection::class
        );
    }

    /**
     * Create the given connection.
     *
     * @param  Connection  $connection
     * @return Connection
     */
    public function add(Connection $connection)
    {
        return $this->buildEntity(
            $this->client->post('connections', [
                'json' => $connection->toArray()
            ]),
            Connection::class
        );
    }

    /**
     * Create or update the given connection.
     *
     * @param  Connection  $connection
     * @return Connection
     */
    public function addOrEdit(Connection $connection)
    {
        return $this->buildEntity(
            $this->client->post('connections/create-update', [
                'json' => $connection->toArray()
            ]),
            Connection::class
        );
    }

    /**
     * Delete the given connection.
     *
     * @param  Connection  $connection
     * @return void
     */
    public function delete(Connection $connection)
    {
        if (! $connection->id) {
            throw new \DomainException('Connection must contain an ID to delete');
        }

        $this->client->delete(sprintf('connections/%s', $connection->id));
    }
}

<?php

namespace Omneo\Modules;

use Omneo\Constraint;
use Omneo\Transaction;
use Omneo\PaginatedCollection;

class Transactions extends Module
{
    /**
     * Fetch listing of transactions.
     *
     * @param  Constraint  $constraint
     * @return PaginatedCollection|Transaction[]
     */
    public function browse(Constraint $constraint = null)
    {
        return $this->buildPaginatedCollection(
            $this->client->get('transactions', $this->applyConstraint($constraint)),
            Transaction::class,
            [$this, __FUNCTION__],
            $constraint
        );
    }

    /**
     * Fetch a single transaction.
     *
     * @param  int  $id
     * @return Transaction
     */
    public function read(int $id)
    {
        return $this->buildEntity(
            $this->client->get(sprintf('transactions/%d', $id)),
            Transaction::class
        );
    }

    /**
     * Edit the given transaction.
     *
     * @param  Transaction  $transaction
     * @return Transaction
     * @throws \DomainException
     */
    public function edit(Transaction $transaction)
    {
        if (! $transaction->id) {
            throw new \DomainException('Transaction must contain an ID to edit');
        }

        return $this->buildEntity(
            $this->client->put(sprintf('transactions/%d', $transaction->id), [
                'json' => $transaction->getDirtyAttributeValues()
            ]),
            Transaction::class
        );
    }

    /**
     * Create the given transaction.
     *
     * @param  Transaction  $transaction
     * @return Transaction
     */
    public function add(Transaction $transaction)
    {
        return $this->buildEntity(
            $this->client->post('transaction', [
                'json' => $transaction->toArray()
            ]),
            Transaction::class
        );
    }

    /**
     * Delete the given webhook.
     *
     * @param  Transaction  $transaction
     * @return void
     */
    public function delete(Transaction $transaction)
    {
        if (! $transaction->id) {
            throw new \DomainException('Transaction must contain an ID to delete');
        }

        $this->client->delete(sprintf('transactions/%d', $transaction->id));
    }
}

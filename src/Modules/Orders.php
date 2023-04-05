<?php

namespace Omneo\Modules;

use Omneo\Constraint;
use Omneo\Order;
use Omneo\PaginatedCollection;

class Orders extends Module
{
    /**
     * Fetch listing of orders.
     *
     * @param  Constraint  $constraint
     * @return PaginatedCollection|Order[]
     */
    public function browse(Constraint $constraint = null)
    {
        return $this->buildPaginatedCollection(
            $this->client->get('orders', $this->applyConstraint($constraint)),
            Order::class,
            [$this, __FUNCTION__],
            $constraint
        );
    }

    /**
     * Fetch a single order.
     *
     * @param  int  $id
     * @return Order
     */
    public function read(int $id)
    {
        return $this->buildEntity(
            $this->client->get(sprintf('orders/%d', $id)),
            Order::class
        );
    }

    /**
     * Edit the given Order.
     *
     * @param  Order  $order
     * @return Order
     * @throws \DomainException
     */
    public function edit(Order $order)
    {
        if (! $order->id) {
            throw new \DomainException('Order must contain an ID to edit');
        }

        return $this->buildEntity(
            $this->client->put(sprintf('orders/%d', $order->id), [
                'json' => $order->getDirtyAttributeValues()
            ]),
            Order::class
        );
    }

    /**
     * Create the given order.
     *
     * @param  Order  $order
     * @return Order
     */
    public function add(Order $order)
    {
        return $this->buildEntity(
            $this->client->post('orders', [
                'json' => $order->toArray()
            ]),
            Order::class
        );
    }

    /**
     * Delete the given order.
     *
     * @param  Order  $order
     * @return void
     */
    public function delete(Order $order)
    {
        if (! $order->id) {
            throw new \DomainException('Order must contain an ID to delete');
        }

        $this->client->delete(sprintf('orders/%d', $order->id));
    }
}

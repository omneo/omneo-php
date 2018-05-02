<?php

namespace Omneo;

use Countable;
use ArrayAccess;
use IteratorAggregate;
use Illuminate\Support\Collection;

class PaginatedCollection implements Countable, ArrayAccess, IteratorAggregate
{
    /**
     * Collection of items.
     *
     * @var Collection
     */
    protected $items;

    /**
     * Current page.
     *
     * @var integer
     */
    protected $currentPage;

    /**
     * Last page.
     *
     * @var integer
     */
    protected $lastPage;

    /**
     * Per page.
     *
     * @var integer
     */
    protected $perPage;

    /**
     * Total number of records.
     *
     * @var integer
     */
    protected $total;

    /**
     * Exectutor callable.
     *
     * Callable receives a constraint to increment through pages.
     *
     * @var callable
     */
    protected $executor;

    /**
     * Constraint for executor.
     *
     * @var Constraint
     */
    protected $constraint;

    /**
     * PaginatedCollection constructor.
     *
     * @param  Collection  $items
     * @param  int  $currentPage
     * @param  int  $lastPage
     * @param  int  $perPage
     * @param  int  $total
     * @param  callable  $executor
     * @param  Constraint  $constraint
     */
    public function __construct(
        Collection $items,
        int $currentPage,
        int $lastPage,
        int $perPage,
        int $total,
        callable $executor = null,
        Constraint $constraint = null
    ) {
        $this->items = $items;
        $this->currentPage = $currentPage;
        $this->lastPage = $lastPage;
        $this->perPage = $perPage;
        $this->total = $total;
        $this->executor = $executor;
        $this->constraint = $constraint;
    }

    /**
     * Return current page.
     *
     * @return int
     */
    public function currentPage()
    {
        return $this->currentPage;
    }

    /**
     * Return last page.
     *
     * @return int
     */
    public function lastPage()
    {
        return $this->lastPage;
    }

    /**
     * Return results per page.
     *
     * @return int
     */
    public function perPage()
    {
        return $this->perPage;
    }

    /**
     * Return total results.
     *
     * @return int
     */
    public function total()
    {
        return $this->total;
    }

    /**
     * Get the number of items for the current page.
     *
     * @return int
     */
    public function count()
    {
        return $this->items->count();
    }

    /**
     * Return true if this is the first page.
     *
     * @return bool
     */
    public function isFirstPage()
    {
        return 1 === $this->currentPage();
    }

    /**
     * Return true if this is the last page.
     *
     * @return bool
     */
    public function isLastPage()
    {
        return $this->currentPage() === $this->lastPage();
    }

    /**
     * Traverse to previous page via the executor.
     *
     * @return PaginatedCollection
     * @throws \Exception
     */
    public function previous()
    {
        if (! $this->executor) {
            throw new \Exception('This paginator does not support page traversal');
        }

        if ($this->isFirstPage()) {
            throw new \Exception('Already on the first page, cannot traverse to previous');
        }

        return call_user_func_array($this->executor, [
            ($this->constraint ?: new Constraint)->page($this->currentPage() - 1)
        ]);
    }

    /**
     * Traverse to next page via the executor.
     *
     * @return PaginatedCollection
     * @throws \Exception
     */
    public function next()
    {
        if (! $this->executor) {
            throw new \Exception('This paginator does not support page traversal');
        }

        if ($this->isLastPage()) {
            throw new \Exception('Already on the last page, cannot traverse to next');
        }

        return call_user_func_array($this->executor, [
            ($this->constraint ?: new Constraint)->page($this->currentPage() + 1)
        ]);
    }

    /**
     * Determine if the given item exists.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->items->has($key);
    }

    /**
     * Get the item at the given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->items->get($key);
    }

    /**
     * Set the item at the given offset.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->items->put($key, $value);
    }

    /**
     * Unset the item at the given key.
     *
     * @param  mixed  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->items->forget($key);
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->items->getIterator();
    }

    /**
     * Determine if the list of items is empty or not.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->items->isEmpty();
    }

    /**
     * Determine if the list of items is not empty.
     *
     * @return bool
     */
    public function isNotEmpty()
    {
        return $this->items->isNotEmpty();
    }

    /**
     * Get the paginator's underlying collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCollection()
    {
        return $this->items;
    }

    /**
     * Set the paginator's underlying collection.
     *
     * @param  \Illuminate\Support\Collection  $collection
     * @return $this
     */
    public function setCollection(Collection $collection)
    {
        $this->items = $collection;
        return $this;
    }

    /**
     * Make dynamic calls into the collection.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->getCollection()->$method(...$parameters);
    }
}

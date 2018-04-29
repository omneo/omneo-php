<?php

namespace Omneo;

class Constraint
{
    /**
     * Bag of constraint operations.
     *
     * @var array
     */
    protected $bag = [];

    /**
     * Dictionary lookup of operations.
     *
     * @var array
     */
    protected $operations = [
        'eq' => '=',
        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<=',
        'in' => 'in'
    ];

    /**
     * Output constraint as array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->bag;
    }

    /**
     * Return true if this constraint is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->bag);
    }

    /**
     * Apply a constraint.
     *
     * @param  string  $column
     * @param  string  $operation  Operation or value if using default "equals"
     * @param  string|null  $value
     * @return static
     * @throws \InvalidArgumentException
     */
    public function where($column, $operation, $value = null)
    {
        // Allow for where('foo', 'bar') for an "equals" operation
        if (null === $value) {
            $value = $operation;
            $operation = '=';
        }

        if (! $remoteOperation = array_search($operation, $this->operations)) {
            throw new \InvalidArgumentException(sprintf(
                'Operation %s is not recognised',
                $operation
            ));
        }

        array_set($this->bag, sprintf('%s.%s', $column, $remoteOperation), $value);

        return $this;
    }

    /**
     * Apply a search constraint.
     *
     * @param  string  $value
     * @return static
     */
    public function search($value)
    {
        array_set($this->bag, 'search', $value);

        return $this;
    }
}

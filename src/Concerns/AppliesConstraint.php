<?php

namespace Omneo\Concerns;

use Omneo\Constraint;

trait AppliesConstraint
{
    /**
     * Get an attribute from the container.
     *
     * @param  Constraint  $constraint
     * @param  array  $query
     * @return array
     */
    protected function applyConstraint(Constraint $constraint = null, $query = [])
    {
        if (! $constraint || $constraint->isEmpty()) return $query;

        return array_merge($query, ['filter' => $constraint->toArray()]);
    }
}
<?php

namespace Omneo\Concerns;

use Omneo\Constraint;

trait AppliesConstraint
{
    /**
     * Get an attribute from the container.
     *
     * @param  Constraint  $constraint
     * @param  array  $options
     * @return array
     */
    protected function applyConstraint(Constraint $constraint = null, array $options = [])
    {
        if (! $constraint) return $options;

        return array_merge_recursive(
            $options,
            array_filter(['query' => $constraint->toArray()])
        );
    }
}
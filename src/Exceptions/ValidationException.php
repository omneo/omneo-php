<?php

namespace Omneo\Exceptions;

use Illuminate\Support\Collection;

class ValidationException extends \Exception
{
    /**
     * Collection of errors.
     *
     * @var Collection
     */
    protected $errors;

    /**
     * ValidationException constructor.
     *
     * @param  Collection  $errors
     * @param  bool  $local  Are these errors from the local validator?
     */
    public function __construct(Collection $errors, bool $local)
    {
        parent::__construct(sprintf(
            '%s validation failed',
            $local ? 'Local' : 'Remote'
        ));

        $this->errors = $errors;
    }

    /**
     * Return collection of errors.
     *
     * @return Collection
     */
    public function errors()
    {
        return $this->errors;
    }
}
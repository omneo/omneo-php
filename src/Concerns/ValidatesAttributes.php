<?php

namespace Omneo\Concerns;

use Illuminate\Support\Collection;
use JsonSchema;
use Omneo\Exceptions\ValidationException;

trait ValidatesAttributes
{
    /**
     * Validate this entity.
     *
     * @return true
     * @throws ValidationException
     */
    public function validate()
    {
        if (! method_exists($this, 'validationSchema') || ! $this->validationSchema()) {
            return true;
        }

        $validator = new JsonSchema\Validator;

        $validator->validate(
            (object) $this->getAttributes(),
            [
                'type' => 'object',
                'properties' => $this->validationSchema()
            ]
        );

        if (! $validator->isValid()) {
            throw new ValidationException(new Collection(
                array_map(function($error) {
                    return sprintf('[%s] %s', $error['property'], $error['message']);
                }, $validator->getErrors())
            ), true);
        }

        return true;
    }
}
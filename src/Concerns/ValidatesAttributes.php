<?php

namespace Omneo\Concerns;

use JsonSchema;
use Illuminate\Support\Collection;
use JsonSchema\Constraints\Constraint;
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

        // Passed by reference into validator
        $attributes = (object) $this->getAttributes();

        $validator->validate(
            $attributes,
            $this->validationSchema(),
            Constraint::CHECK_MODE_APPLY_DEFAULTS
        );

        if (! $validator->isValid()) {
            throw new ValidationException(new Collection(
                array_pluck($validator->getErrors(), 'message')
            ), true);
        }

        $this->setAttributes((array) $attributes);

        return true;
    }
}
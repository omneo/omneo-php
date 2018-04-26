<?php

namespace Omneo;

class CustomField extends Entity
{
    /**
     * Return JSON validation schema.
     *
     * @return  array
     */
    public function validationSchema()
    {
        return [
            'namespace' => ['type' => 'string'],
            'handle' => ['type' => 'string'],
            'value' => ['type' => 'string'],
            'type' => ['type' => 'string', 'default' => 'string'],
        ];
    }
}

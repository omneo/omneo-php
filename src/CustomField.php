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
            'type' => 'object',
            'properties' => [
                'namespace' => ['type' => 'string'],
                'handle' => ['type' => 'string'],
                'value' => ['type' => 'string'],
                'type' => ['type' => 'string', 'default' => 'string']
            ],
            'required' => ['namespace', 'handle', 'value', 'type']
        ];
    }
}

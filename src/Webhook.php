<?php

namespace Omneo;

class Webhook extends Entity
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
                'event' => ['type' => 'string'],
                'url' => ['type' => 'string']
            ],
            'required' => ['event', 'url']
        ];
    }
}

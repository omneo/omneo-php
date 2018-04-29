<?php

namespace Omneo\Modules;

use Omneo\Tenant;
use Omneo\Contracts;

trait BuildsModules
{
    /**
     * Return webhooks module.
     *
     * @return Webhooks
     */
    public function webhooks()
    {
        return new Webhooks($this);
    }

    /**
     * Return custom fields module.
     *
     * @param  Contracts\HasUri  $owner
     * @return CustomFields
     */
    public function customFields(Contracts\HasUri $owner = null)
    {
        return new CustomFields($this, $owner ?: new Tenant);
    }

    /**
     * Return request verifier module.
     *
     * @return  RequestVerifier
     */
    public function requestVerifier()
    {
        return new RequestVerifier($this);
    }
}
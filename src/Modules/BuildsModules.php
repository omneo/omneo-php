<?php

namespace Omneo\Modules;

use Omneo\Tenant;
use Omneo\Contracts;

trait BuildsModules
{
    /**
     * Return profiles module.
     *
     * @return Profiles
     */
    public function profiles()
    {
        return new Profiles($this);
    }

    /**
     * Return identities module.
     *
     * @param  Contracts\HasUri  $owner
     * @return Identities
     */
    public function identities(Contracts\HasUri $owner)
    {
        return new Identities($this, $owner);
    }

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
     * Return interactions module.
     *
     * @return Interactions
     */
    public function interactions()
    {
        return new Interactions($this);
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
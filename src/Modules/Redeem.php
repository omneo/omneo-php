<?php

namespace Omneo\Modules;

use Omneo\Client;
use Omneo\Contracts;
use Omneo\Redeemable;
use Omneo\Redemption;

class Redeem extends Module
{
    /**
     * Owner entity.
     *
     * @var Contracts\HasUri
     */
    protected $owner;

    /**
     * Redeem constructor.
     *
     * @param  Client  $client
     * @param  Contracts\HasUri  $owner
     */
    public function __construct(Client $client, Contracts\HasUri $owner = null)
    {
        parent::__construct($client);

        $this->owner = $owner;
    }

    /**
     * Redeem using a redeemable strategy.
     *
     * @param Redeemable $redeemable
     * @return mixed
     */
    public function strategy(Redeemable $redeemable)
    {
        return $this->buildEntity(
            $this->client->post(sprintf(
                '%s/%s',
                $this->owner->uri(),
                'redeem'
            ), [
                'json' => $redeemable->toArray()
            ]),
            Redemption::class
        );
    }
}

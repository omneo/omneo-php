<?php

namespace Omneo;

use Illuminate\Contracts\Support\Arrayable;

class Redeemable implements Arrayable
{
    /**
     * Points strategy identifier.
     */
    const POINTS = 'points';

    /**
     * Rewards strategy identifier.
     */
    const REWARDS = 'rewards';

    /**
     * Amount to redeem.
     *
     * @var float
     */
    protected $amount;

    /**
     * Strategy to use (e.g. points, rewards)
     *
     * @var array|null
     */
    protected $strategy;

    /**
     * Redeemable constructor.
     *
     * @param float $amount
     */
    public function __construct(float $amount)
    {
        $this->amount = $amount;
    }

    /**
     * Set the strategy.
     *
     * @param array|string|null $strategy
     * @return $this
     */
    public function strategy($strategy = null)
    {
        $this->strategy = is_array($strategy) ? $strategy : func_get_args();

        return $this;
    }

    /**
     * Get the array representation of the instance.
     *
     * @return array
     */
    public function toArray()
    {
        return array_filter([
            'amount' => $this->amount,
            'strategy' => $this->strategy
        ]);
    }
}

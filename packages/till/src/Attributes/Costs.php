<?php

namespace ArtisanBuild\Till\Attributes;

use ArtisanBuild\Till\SubscriptionPlans\Ledgers;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Costs
{
    public function __construct(public Ledgers $ledger, public int $amount = 1) {}
}

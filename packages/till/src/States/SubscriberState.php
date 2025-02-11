<?php

namespace ArtisanBuild\Till\States;

use Carbon\CarbonInterface;
use Thunk\Verbs\State;

class SubscriberState extends State
{
    public ?int $plan_id = null;

    public ?CarbonInterface $renews_at = null;

    public ?CarbonInterface $expires_at = null;
}

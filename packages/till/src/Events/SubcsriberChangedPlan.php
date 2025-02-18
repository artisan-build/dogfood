<?php

namespace ArtisanBuild\Till\Events;

use ArtisanBuild\Till\States\SubscriberState;
use ArtisanBuild\Till\Traits\HandlesSubscriptionChanges;
use Carbon\CarbonInterface;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class SubcsriberChangedPlan extends Event
{
    use HandlesSubscriptionChanges;

    #[StateId(SubscriberState::class)]
    public int $subscriber_id;

    public string $plan_id;

    public ?CarbonInterface $renews_at = null;

    public ?CarbonInterface $expires_at = null;
}

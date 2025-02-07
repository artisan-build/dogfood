<?php

namespace ArtisanBuild\Till\Events;

use ArtisanBuild\Till\Actions\GetDefaultPlan;
use ArtisanBuild\Till\States\SubscriberState;
use ArtisanBuild\Till\Traits\HandlesSubscriptionChanges;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class SubscriptionCacheUpdated extends Event
{
    use HandlesSubscriptionChanges;

    #[StateId(SubscriberState::class)]
    public int $subscriber_id;

    public function apply(SubscriberState $state): void
    {
        $state->plan_id ??= app(GetDefaultPlan::class)()->id;
    }
}

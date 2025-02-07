<?php

namespace ArtisanBuild\Till\Events;

use ArtisanBuild\Till\Actions\GetDefaultPlan;
use ArtisanBuild\Till\States\SubscriberState;
use ArtisanBuild\Till\Traits\HandlesSubscriptionChanges;
use Carbon\CarbonInterface;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class SubscriptionCacheUpdated extends Event
{
    use HandlesSubscriptionChanges;

    #[StateId(SubscriberState::class)]
    public int $subscriber_id;

    public ?CarbonInterface $expires_at = null;

    public ?CarbonInterface $renews_at = null;

    public function apply(SubscriberState $state): void
    {
        $state->plan_id ??= data_get(app(GetDefaultPlan::class)(), 'id');
    }
}

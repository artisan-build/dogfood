<?php

namespace ArtisanBuild\Till\Events;

use ArtisanBuild\Till\Actions\GetPlanById;
use ArtisanBuild\Till\Contracts\PlanInterface;
use ArtisanBuild\Till\States\SubscriberState;
use ArtisanBuild\Till\Traits\HandlesSubscriptionChanges;
use Carbon\CarbonInterface;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class NewSubscriberAddedToDefaultPlan extends Event
{
    use HandlesSubscriptionChanges;

    #[StateId(SubscriberState::class)]
    public int $subscriber_id;

    public int $plan_id;

    public ?CarbonInterface $renews_at = null;

    public ?CarbonInterface $expires_at = null;

    public function validate(SubscriberState $state): bool
    {
        return $state->plan_id === null
            && app(GetPlanById::class)($this->plan_id) instanceof PlanInterface;
    }

    public function apply(SubscriberState $state): void
    {
        $state->plan_id = $this->plan_id;
        $state->expires_at = $this->expires_at;
        $state->renews_at = $this->renews_at;

        $state->limits = data_get(app(GetPlanById::class)($this->plan_id), 'limits', []);
    }
}

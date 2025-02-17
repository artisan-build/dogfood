<?php

namespace ArtisanBuild\Till\Events;

use ArtisanBuild\Adverbs\Traits\SimpleApply;
use ArtisanBuild\Till\Actions\GetPlanById;
use ArtisanBuild\Till\Attributes\ImpactsAbilities;
use ArtisanBuild\Till\Contracts\PlanInterface;
use ArtisanBuild\Till\States\SubscriberState;
use ArtisanBuild\Till\Traits\HandlesSubscriptionChanges;
use Carbon\CarbonInterface;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

#[ImpactsAbilities]
class SubscriptionStarted extends Event
{
    use HandlesSubscriptionChanges;
    use SimpleApply;

    #[StateId(SubscriberState::class)]
    public int $subscriber_id;

    public string $plan_id;

    public ?CarbonInterface $renews_at = null;

    public ?CarbonInterface $expires_at = null;

    public function validate(SubscriberState $state): bool
    {
        return $state->plan_id === null
            && app(GetPlanById::class)($this->plan_id) instanceof PlanInterface;
    }
}

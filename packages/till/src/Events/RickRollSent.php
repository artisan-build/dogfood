<?php

namespace ArtisanBuild\Till\Events;

use ArtisanBuild\Till\Attributes\Costs;
use ArtisanBuild\Till\States\SubscriberState;
use ArtisanBuild\Till\SubscriptionPlans\Ledgers;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

#[Costs(Ledgers::RickRolls, 1)]
class RickRollSent extends Event
{
    #[StateId(SubscriberState::class)]
    public int $subscriber_id;
}

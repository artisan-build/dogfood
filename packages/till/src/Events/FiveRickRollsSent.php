<?php

namespace ArtisanBuild\Till\Events;

use ArtisanBuild\Till\Attributes\Costs;
use ArtisanBuild\Till\States\SubscriberState;
use ArtisanBuild\Till\SubscriptionPlans\Ledgers;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

#[Costs(Ledgers::RickRolls, 5)]
class FiveRickRollsSent extends Event
{
    #[StateId(SubscriberState::class)]
    public int $subscriber_id;

    public function applied() {}
}

<?php

namespace ArtisanBuild\Till\Events;

use ArtisanBuild\Till\States\SubscriberState;
use ArtisanBuild\Till\SubscriptionPlans\Ledgers;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class LedgerDebited extends Event
{
    #[StateId(SubscriberState::class)]
    public int $subscriber_id;

    public Ledgers $ledger;

    public int $amount;

    public function apply(SubscriberState $state): void
    {
        $state->wallet[$this->ledger->name] = isset($state->wallet[$this->ledger->name])
            ? $state->wallet[$this->ledger->name] + $this->amount
            : $this->amount;
    }
}

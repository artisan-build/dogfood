<?php

namespace ArtisanBuild\Till\Listeners;

use ArtisanBuild\Till\Attributes\Costs;
use ArtisanBuild\Till\States\SubscriberState;
use ReflectionAttribute;
use ReflectionClass;
use Thunk\Verbs\Attributes\Hooks\On;
use Thunk\Verbs\Event;
use Thunk\Verbs\Lifecycle\Phase;

class ProcessesLedgerTransactionsListener
{
    #[On(Phase::Fired)]
    public function processLedgerTransaction(Event $event): void
    {
        $reflection = new ReflectionClass($event);
        $costs = $reflection->getAttributes(Costs::class);

        if (empty($costs)) {
            return;
        }

        $state = $event->state(SubscriberState::class);
        assert($state instanceof SubscriberState);

        collect($costs)
            ->each(fn (ReflectionAttribute $cost) => $state->spend(ledger: $cost->getArguments()[0], amount: $cost->getArguments()[1])
            );
    }
}

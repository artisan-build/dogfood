<?php

namespace ArtisanBuild\Till\Listeners;

use ArtisanBuild\Till\Attributes\Costs;
use ArtisanBuild\Till\Exceptions\LedgerBalanceTooLowException;
use ArtisanBuild\Till\States\SubscriberState;
use ArtisanBuild\Till\SubscriptionPlans\Abilities\Spend;
use ReflectionAttribute;
use ReflectionClass;
use Thunk\Verbs\Attributes\Hooks\On;
use Thunk\Verbs\Event;
use Thunk\Verbs\Lifecycle\Phase;

class AuthorizesLedgerTransactionsListener
{
    #[On(Phase::Boot)]
    public function authorizeLedgerTransaction(Event $event): void
    {
        $reflection = new ReflectionClass($event);

        $costs = $reflection->getAttributes(Costs::class);

        if (empty($costs)) {
            return;
        }

        collect($reflection->getAttributes(Costs::class))->each(function (ReflectionAttribute $cost) use ($event): void {
            throw_if(! app(Spend::class)(
                state: $event->state(SubscriberState::class),
                ledger: $cost->getArguments()[0],
                attempted_spend: $cost->getArguments()[1],
            ), new LedgerBalanceTooLowException("Insufficient Balance: {$cost->getArguments()[0]->name}"));
        });
    }
}

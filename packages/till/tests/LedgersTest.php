<?php

use ArtisanBuild\Till\Enums\TestPlans;
use ArtisanBuild\Till\Events\FiveRickRollsSent;
use ArtisanBuild\Till\Events\LedgerDebited;
use ArtisanBuild\Till\Events\RickRollSent;
use ArtisanBuild\Till\Events\SubscriptionStarted;
use ArtisanBuild\Till\Exceptions\LedgerBalanceTooLowException;
use ArtisanBuild\Till\States\SubscriberState;
use ArtisanBuild\Till\SubscriptionPlans\Ledgers;
use Carbon\Carbon;
use Thunk\Verbs\Facades\Verbs;

beforeEach(function (): void {
    Verbs::commitImmediately();
    SubscriptionStarted::commit(
        subscriber_id: 1,
        plan_id: TestPlans::CityTrollPlan->value,
    );
});
describe('usage ledger on the subscriber state', function (): void {
    it('adds a single transaction', function (): void {
        RickRollSent::commit(
            subscriber_id: 1,
        );

        $state = SubscriberState::load(1);

        expect($state->transactions)->toHaveCount(1)
            ->and($state->transactions[0]['ledger'])->toBe(Ledgers::RickRolls->name);
    });

    it('adds multiple transactions', function (): void {

        FiveRickRollsSent::commit(
            subscriber_id: 1,
        );

        $state = SubscriberState::load(1);

        expect($state->transactions)->toHaveCount(5)
            ->and($state->transactions[0]['ledger'])->toBe(Ledgers::RickRolls->name);
    });

    it('throws if the user is out of credits', function (): void {

        FiveRickRollsSent::commit(
            subscriber_id: 1,
        );

        FiveRickRollsSent::commit(
            subscriber_id: 1,
        );

        FiveRickRollsSent::fire(
            subscriber_id: 1,
        );
    })->throws(LedgerBalanceTooLowException::class);

    it('creates an entry in the wallet if one does not exist', function (): void {
        LedgerDebited::commit(
            subscriber_id: 1,
            ledger: Ledgers::RickRolls,
            amount: 100,
        );

        $state = SubscriberState::load(1);

        expect($state->wallet[Ledgers::RickRolls->name])->toBe(100);
    });

    it('adds to an existing wallet balance', function (): void {
        LedgerDebited::commit(
            subscriber_id: 1,
            ledger: Ledgers::RickRolls,
            amount: 100,
        );

        $state = SubscriberState::load(1);

        expect($state->wallet[Ledgers::RickRolls->name])->toBe(100);

        LedgerDebited::commit(
            subscriber_id: 1,
            ledger: Ledgers::RickRolls,
            amount: 100,
        );

        $state = SubscriberState::load(1);

        expect($state->wallet[Ledgers::RickRolls->name])->toBe(200);
    });

    it('allows the wallet balance to be used if needed', function (): void {
        LedgerDebited::commit(
            subscriber_id: 1,
            ledger: Ledgers::RickRolls,
            amount: 100,
        );

        FiveRickRollsSent::fire(
            subscriber_id: 1,
        );

        FiveRickRollsSent::fire(
            subscriber_id: 1,
        );

        $state = SubscriberState::load(1);

        expect($state->wallet[Ledgers::RickRolls->name])->toBe(95);

    });

    it('uses the wallet for remainder if balance does not quite cover it', function (): void {
        LedgerDebited::commit(
            subscriber_id: 1,
            ledger: Ledgers::RickRolls,
            amount: 100,
        );

        RickRollSent::fire(
            subscriber_id: 1,
        );

        FiveRickRollsSent::fire(
            subscriber_id: 1,
        );

        $state = SubscriberState::load(1);

        expect($state->wallet[Ledgers::RickRolls->name])->toBe(99);

        FiveRickRollsSent::fire(
            subscriber_id: 1,
        );

        $state = SubscriberState::load(1);

        expect($state->wallet[Ledgers::RickRolls->name])->toBe(94);

    });

    it('resets when expected', function (): void {
        FiveRickRollsSent::fire(
            subscriber_id: 1,
        );

        $state = SubscriberState::load(1);

        expect($state->transactions)->toHaveCount(5)
            ->and($state->transactions[0]['ledger'])->toBe(Ledgers::RickRolls->name);

        Carbon::setTestNow(now()->addMonth()->addMinute());

        FiveRickRollsSent::fire(
            subscriber_id: 1,
        );

        $state = SubscriberState::load(1);

        expect($state->transactions)->toHaveCount(10)
            ->and($state->transactions[0]['ledger'])->toBe(Ledgers::RickRolls->name);
    });
});

<?php

namespace ArtisanBuild\Till\SubscriptionPlans\Abilities;

use ArtisanBuild\Till\Enums\LedgerPeriods;
use ArtisanBuild\Till\States\SubscriberState;
use ArtisanBuild\Till\SubscriptionPlans\Ledgers;
use Illuminate\Support\Facades\Context;
use Thunk\Verbs\State;

class Spend
{
    public function __invoke(State $state, Ledgers $ledger, int $attempted_spend = 1): bool
    {
        // Make sure we have a fresh state because these might fire within a single event
        $state = $state->fresh();
        // This should definitely always be true
        assert($state instanceof SubscriberState);

        $ledgers = data_get($state->plan(), 'ledgers');

        // Ledgers aren't defined for the plan, so we assume no available balance
        if (! is_array($ledgers) || empty($ledgers)) {
            return false;
        }

        // Get the array of the specific ledger that we are working with
        $line = collect($ledgers)->firstWhere('ledger', $ledger);

        // This item doesn't have a ledger, so we assume the plan does not include it.
        if (! $line) {
            return false;
        }

        // Clear the wallet in case this is not the first run in this request
        Context::forget('wallet'.$ledger->name);

        // Count usage within the specified timeframe
        $period = $line['period'];
        assert($period instanceof LedgerPeriods);

        $timeframe = match ($period) {
            LedgerPeriods::Minute => now()->subMinute()->timestamp,
            LedgerPeriods::Hour => now()->subHour()->timestamp,
            LedgerPeriods::Day => now()->subDay()->timestamp,
            LedgerPeriods::Week => now()->subWeek()->timestamp,
            LedgerPeriods::Month => now()->subMonth()->timestamp,
            LedgerPeriods::Year => now()->subYear()->timestamp,
            LedgerPeriods::Life => now()->subCenturies(2)->timestamp,
        };

        $used = collect($state->transactions)
            ->where('ledger', $ledger->name)
            ->where('at', '>=', $timeframe)
            ->count();

        // This is the included limit minus what has already been used.
        $pre_run_balance = $line['limit'] - $used;

        // We have enough remaining included balance to cover this.
        if ($pre_run_balance >= $attempted_spend) {
            return true;
        }

        // We don't have enough in the included balance, so we have to dip into the wallet.
        // Get the balance remaining (if any) in the wallet
        $wallet_balance = data_get($state->wallet, $ledger->name, 0);

        // Don't have enough to do this even with the wallet balance

        if ($wallet_balance + $pre_run_balance < $attempted_spend) {
            return false;
        }

        // We do have enough in the wallet, so let's figure out how much we have to take from the wallet.

        $wallet = $pre_run_balance > 0
            ? $attempted_spend - $pre_run_balance
            : $attempted_spend;

        Context::add('wallet'.$ledger->name, $wallet);

        return true;

    }
}

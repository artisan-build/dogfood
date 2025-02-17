<?php

namespace ArtisanBuild\Till\States;

use ArtisanBuild\Till\Actions\GetPlanById;
use ArtisanBuild\Till\Contracts\PlanInterface;
use ArtisanBuild\Till\SubscriptionPlans\Ledgers;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Context;
use Thunk\Verbs\State;

class SubscriberState extends State
{
    public ?string $plan_id = null;

    public ?CarbonInterface $renews_at = null;

    public ?CarbonInterface $expires_at = null;

    public array $wallet = [];

    public array $transactions = [];

    public function plan(): PlanInterface
    {
        return app(GetPlanById::class)($this->plan_id);
    }

    public function spend(Ledgers $ledger, int $amount = 1): void
    {
        foreach (range(1, $amount) as $times) {
            $this->transactions[] = ['ledger' => $ledger->name, 'at' => now()->timestamp];
        }
        if (Context::has('wallet'.$ledger->name) && data_get($this->wallet, $ledger->name) !== null) {
            $this->wallet[$ledger->name] -= Context::get('wallet'.$ledger->name);
            Context::forget('wallet'.$ledger->name);
        }
    }

    public function can(string $action, array $arguments): bool
    {
        $arguments['state'] = $this;

        return app($action)(...$arguments);
    }

    public function deposit(string $ledger, int|float $amount = 1): void
    {
        if (array_key_exists($ledger, $this->wallet)) {
            $this->wallet[$ledger] += $amount;
        } else {
            $this->wallet[$ledger] = $amount;
        }
    }

    public function withdraw(string $ledger, int|float $amount = 1): void
    {
        $this->wallet[$ledger] -= $amount;
    }
}

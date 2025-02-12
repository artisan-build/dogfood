<?php

namespace ArtisanBuild\Till\SubscriptionPlans;

use ArtisanBuild\Till\Contracts\PlanInterface;
use ArtisanBuild\Till\Enums\Currencies;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BasePlan implements PlanInterface
{
    public string $id;

    public bool $current = false;

    public $currency = Currencies::USD;

    public function __construct()
    {
        $this->id = Str::of(last(explode('\\', static::class)))->headline()->slug();
        $this->current = Auth::user()?->subscription()->plan_id === $this->id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function subscribeUrl()
    {
        return '#';
    }
}

<?php

namespace ArtisanBuild\Till\SubscriptionPlans;

use ArtisanBuild\Till\Enums\Currencies;
use ArtisanBuild\Till\States\SubscriberState;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BasePlan
{
    public string $id;
    public bool $current = false;
    public $currency = Currencies::USD;

    public function __construct()
    {
        $this->id = Str::of(last(explode('\\', static::class)))->headline()->slug();
        $this->current = Auth::user()?->subscription()->plan_id === $this->id;
    }

    public function subscribeUrl()
    {
        return '#';
    }
}

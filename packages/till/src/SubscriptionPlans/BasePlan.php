<?php

namespace ArtisanBuild\Till\SubscriptionPlans;

use Illuminate\Support\Str;

class BasePlan
{
    public string $id;

    public function __construct()
    {
        $this->id = Str::of(last(explode('\\', static::class)))->headline()->slug();
    }
}

<?php

namespace ArtisanBuild\Till\States;

use Thunk\Verbs\SingletonState;

class SubscriptionCountState extends SingletonState
{
    public array $subscribers = [];

    public function increment(string $plan): void
    {
        $this->subscribers[$plan] = ($this->subscribers[$plan] ?? 0) + 1;
    }

    public function decrement(string $plan): void
    {
        $this->subscribers[$plan]--;
    }
}

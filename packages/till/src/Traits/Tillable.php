<?php

namespace ArtisanBuild\Till\Traits;

use ArtisanBuild\Till\Events\SubscriptionCacheUpdated;
use ArtisanBuild\Till\States\SubscriberState;
use Illuminate\Support\Facades\Cache;

trait Tillable
{
    public function subscriberId(): int
    {
        return config('till.team_mode')
            ? $this->current_team_id
            : $this->id;
    }

    public function subscription(): ?SubscriberState
    {
        return SubscriberState::load($this->subscriberId());
    }

    public function abilities(): array
    {
        return Cache::get('subscription-'.$this->subscriberId(), SubscriptionCacheUpdated::commit(
            subscriber_id: $this->subscriberId()
        )) ?? [];
    }

    public function ableTo(string $item): bool
    {
        return data_get($this->abilities(), $item, false);
    }
}

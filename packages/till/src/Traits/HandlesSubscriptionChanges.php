<?php

namespace ArtisanBuild\Till\Traits;

use ArtisanBuild\Till\Actions\GetPlanById;
use ArtisanBuild\Till\States\SubscriberState;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;

trait HandlesSubscriptionChanges
{
    public function handle(): array
    {
        $abilities = [];
        foreach (data_get(app(GetPlanById::class)($this->state(SubscriberState::class)->plan_id), 'can') as $ability) {
            $abilities[$ability[0]] = app($ability[0])(...$ability[1]);
        }
        Cache::put('subscription-'.$this->subscriber_id, $abilities, $this->getCacheExpiration());

        return $abilities;
    }

    protected function getCacheExpiration(): ?CarbonInterface
    {
        if ($this->expires_at && $this->renews_at) {
            return $this->expires_at->lt($this->renews_at) ? $this->expires_at : $this->renews_at;
        }

        return $this->expires_at ?? $this->renews_at;
    }
}

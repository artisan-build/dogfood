<?php

namespace ArtisanBuild\Till\Actions;

use ArtisanBuild\Till\States\SubscriberState;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Cache;

class RefreshSubscriptionCache
{
    public function __construct(protected SubscriberState $state) {}

    public function __invoke(?User $user = null, ?CarbonInterface $expiration = null): array
    {
        $abilities = [];
        foreach (data_get(app(GetPlanById::class)($this->state->plan_id), 'can') as $ability) {
            $abilities[last(explode('\\', (string) $ability[0]))] = app($ability[0])(...$ability[1]);
        }

        Cache::put('subscription-'.$this->state->id, $abilities, $expiration ?? $this->getCacheExpiration());

        return $abilities;
    }

    protected function getCacheExpiration(): ?CarbonInterface
    {
        if ($this->state->expires_at && $this->state->renews_at) {
            return $this->state->expires_at->lt($this->state->renews_at) ? $this->state->expires_at : $this->state->renews_at;
        }

        return $this->state->expires_at ?? $this->state->renews_at;
    }
}

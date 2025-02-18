<?php

namespace ArtisanBuild\Till\Traits;

use ArtisanBuild\Till\Actions\GetPlanById;
use ArtisanBuild\Till\States\SubscriberState;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

trait HandlesSubscriptionChanges
{
    public function handle(): array
    {
        /** @var SubscriberState $state */
        $state = $this->state(SubscriberState::class);

        if (! property_exists($state, 'plan_id')) {
            throw new RuntimeException('State class must have a plan_id property');
        }

        $abilities = [];
        foreach (data_get(app(GetPlanById::class)($state->plan_id), 'can') as $ability) {
            $abilities[last(explode('\\', (string) $ability[0]))] = app($ability[0])(...$ability[1]);
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

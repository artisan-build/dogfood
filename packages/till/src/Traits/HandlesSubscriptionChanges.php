<?php

namespace ArtisanBuild\Till\Traits;

use ArtisanBuild\Till\Actions\RefreshSubscriptionCache;
use ArtisanBuild\Till\States\SubscriberState;

trait HandlesSubscriptionChanges
{
    public function handle(): array
    {
        $state = $this->state(SubscriberState::class);

        assert($state instanceof SubscriberState);

        return (new RefreshSubscriptionCache($state))();
    }
}

<?php

namespace ArtisanBuild\Till\Traits;

use ArtisanBuild\Till\Events\SubscriptionCacheUpdated;
use Illuminate\Support\Facades\Auth;

trait AffectsAbilities
{
    public function fired(): void
    {
        defer(fn () => SubscriptionCacheUpdated::commit(
            subscriber_id: config('till.team_mode')
             ? Auth::user()->current_team_id
             : Auth::id()
        ));
    }
}

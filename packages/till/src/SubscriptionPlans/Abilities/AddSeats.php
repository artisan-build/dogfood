<?php

namespace ArtisanBuild\Till\SubscriptionPlans\Abilities;

use Illuminate\Support\Facades\Auth;

class AddSeats
{
    public function __invoke(?int $limit = null)
    {
        if ($limit === null) {
            return true;
        }

        return Auth::user()->currentTeam->allUsers()->count() < $limit;
    }
}

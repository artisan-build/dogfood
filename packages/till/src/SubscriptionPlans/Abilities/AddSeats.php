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

        if (!Auth::check()) {
            return false;
        }

        if (Auth::user()->currentTeam === null) {
            return false;
        }

        return Auth::user()->currentTeam->allUsers()->count() < $limit;
    }
}

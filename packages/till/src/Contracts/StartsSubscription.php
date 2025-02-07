<?php

namespace ArtisanBuild\Till\Contracts;

use Carbon\CarbonInterface;
use Illuminate\Foundation\Auth\User;

interface StartsSubscription
{
    public function __invoke(User $user, PlanInterface $plan, CarbonInterface $expires): void;
}

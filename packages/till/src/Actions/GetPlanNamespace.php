<?php

namespace ArtisanBuild\Till\Actions;

use Illuminate\Support\Str;

class GetPlanNamespace
{
    public function __invoke()
    {
        $path = config('till.plan_path');

        if ($path === base_path('packages/till/src/SubscriptionPlans')) {
            return 'ArtisanBuild\Till\SubscriptionPlans';
        }

        $app_path = app_path();

        $relative = Str::after($path, $app_path.DIRECTORY_SEPARATOR);

        return 'App\\'.str_replace(DIRECTORY_SEPARATOR, '\\', $relative);
    }
}

<?php

namespace ArtisanBuild\Till\Actions;

use ArtisanBuild\Till\Attributes\DefaultPlan;
use ReflectionClass;

class GetVisiblePlans
{
    public function __construct(private readonly GetActivePlans $plans) {}

    public function __invoke()
    {
        if (config('till.show_free_plan')) {
            return ($this->plans)();
        }

        return ($this->plans)()->filter(function ($plan) {
            $reflection = new ReflectionClass($plan);

            return empty($reflection->getAttributes(DefaultPlan::class));
        });

    }
}

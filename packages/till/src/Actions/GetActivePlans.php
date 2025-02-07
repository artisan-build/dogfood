<?php

namespace ArtisanBuild\Till\Actions;

use ArtisanBuild\Till\Attributes\UnavailablePlan;
use ReflectionClass;

class GetActivePlans
{
    public function __construct(private readonly GetPlans $plans) {}

    public function __invoke()
    {
        return ($this->plans)()->filter(function ($plan) {
            $reflection = new ReflectionClass($plan);

            return empty($reflection->getAttributes(UnavailablePlan::class));
        });

    }
}

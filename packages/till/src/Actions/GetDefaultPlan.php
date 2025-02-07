<?php

namespace ArtisanBuild\Till\Actions;

use ArtisanBuild\Till\Attributes\DefaultPlan;
use ArtisanBuild\Till\Contracts\PlanInterface;
use ReflectionClass;

class GetDefaultPlan
{
    public function __construct(private readonly GetPlans $plans) {}

    public function __invoke(): PlanInterface
    {
        return ($this->plans)()->filter(function ($plan) {
            $reflection = new ReflectionClass($plan);

            return ! empty($reflection->getAttributes(DefaultPlan::class));
        })->sole();
    }
}

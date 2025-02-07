<?php

namespace ArtisanBuild\Till\Actions;

use ArtisanBuild\Till\Contracts\PlanInterface;
use ReflectionClass;

class GetPlanById
{
    public function __construct(private readonly GetPlans $plans) {}

    public function __invoke(int $id): PlanInterface
    {
        return ($this->plans)()->filter(function ($plan) use ($id) {
            $reflection = new ReflectionClass($plan);

            return $reflection->getProperty('id')->getDefaultValue() === $id;
        })->sole();
    }
}

<?php

namespace ArtisanBuild\Till\Actions;

use ArtisanBuild\Till\Contracts\PlanInterface;
use Illuminate\Support\Str;
use ReflectionClass;

class GetPlanById
{
    public function __construct(private readonly GetPlans $plans) {}

    public function __invoke(string $id): PlanInterface
    {
        return ($this->plans)()->filter(function ($plan) use ($id) {
            $reflection = new ReflectionClass($plan);

            return Str::of($reflection->getShortName())->headline()->slug()->toString() === $id;
        })->sole();
    }
}

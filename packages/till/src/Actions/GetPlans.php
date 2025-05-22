<?php

namespace ArtisanBuild\Till\Actions;

use ArtisanBuild\Till\Attributes\IndividualPlan;
use ArtisanBuild\Till\Attributes\TeamPlan;
use ArtisanBuild\Till\Enums\PlanTerms;
use ArtisanBuild\Till\SubscriptionPlans\BasePlan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;

class GetPlans
{
    public function __invoke(): Collection
    {
        return collect(File::files(config('till.plan_path')))
            ->filter(fn ($file) => Str::endsWith($file->getFilename(), '.php'))
            ->reject(fn ($file): bool => Str::startsWith($file->getFilename(), 'BasePlan'))
            ->map(function ($file) {
                $contents = File::get($file->getPathname());

                // Extract namespace
                $namespace = '';
                if (preg_match('/^namespace\s+(.+?);/m', $contents, $matches)) {
                    $namespace = $matches[1];
                }

                // Extract class name
                if (preg_match('/^class\s+(\w+)/m', $contents, $matches)) {
                    $class = $matches[1];

                    return $namespace ? $namespace.'\\'.$class : $class;
                }

                return null;
            })
            ->filter() // Remove null values (files without classes)
            ->filter(fn ($class) => class_exists($class))
            ->filter(function ($plan) {
                $attribute = config('till.team_mode') ? TeamPlan::class : IndividualPlan::class;

                $reflection = new ReflectionClass($plan);

                return ! empty($reflection->getAttributes($attribute));
            })
            ->map(fn (string $plan): BasePlan => new $plan)
            ->sort(function (BasePlan $plan_a, BasePlan $plan_b) {
                $a = $plan_a->prices[PlanTerms::Life->value] ?? $plan_a->prices[PlanTerms::Year->value] ?? $plan_a->prices[PlanTerms::Month->value] ?? $plan_a->prices[PlanTerms::Week->value];
                $b = $plan_b->prices[PlanTerms::Life->value] ?? $plan_b->prices[PlanTerms::Year->value] ?? $plan_b->prices[PlanTerms::Month->value] ?? $plan_b->prices[PlanTerms::Week->value];

                return $a <=> $b;
            });
    }
}

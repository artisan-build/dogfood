<?php

namespace ArtisanBuild\Till\Actions;

use ArtisanBuild\Till\Attributes\DefaultPlan;
use ArtisanBuild\Till\Attributes\IndividualPlan;
use ArtisanBuild\Till\Attributes\TeamPlan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;

class GetPlans
{
    public function __invoke(): Collection
    {
        return collect(File::files(config('till.plan_path')))
            ->filter(fn ($file) => Str::endsWith($file->getFilename(), '.php'))
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
            ->map(fn ($class) => new $class)->filter(function ($plan) {
                $attribute = config('till.team_mode') ? TeamPlan::class : IndividualPlan::class;

                $reflection = new ReflectionClass($plan);

                return ! empty($reflection->getAttributes($attribute));
            })->map(function ($plan) {
                $user = Auth::user();

                if ($user === null) {
                    return $plan;
                }

                $reflection = new ReflectionClass($user);

                if (! $reflection->hasMethod('subscription')) {
                    return $plan;
                }

                $reflection = new ReflectionClass($plan);

                $id = $reflection->getProperty('id')->getDefaultValue();

                if ($user->subscription()->plan_id === $id) {
                    $plan->current = true;
                }

                if ($user->subscription()->plan_id === null && ! empty($reflection->getAttributes(DefaultPlan::class))) {
                    $plan->current = true;
                }

                return $plan;
            })->sortBy(['prices.month.price', 'price.year.price', 'price.life.price']);
    }
}

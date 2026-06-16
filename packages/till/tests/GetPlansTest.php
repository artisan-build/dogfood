<?php

declare(strict_types=1);

use ArtisanBuild\Till\Actions\GetPlans;
use Illuminate\Support\Collection;

it('gets all of the plans', function (): void {
    expect(resolve(GetPlans::class)())->toBeInstanceOf(Collection::class)->toHaveCount(4)
        ->and(resolve(GetPlans::class)()->first())->toHaveProperty('id');
});

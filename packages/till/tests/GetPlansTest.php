<?php

use ArtisanBuild\Till\Actions\GetPlans;
use Illuminate\Support\Collection;

it('gets all of the plans', function (): void {
    expect(app(GetPlans::class)())->toBeInstanceOf(Collection::class)->toHaveCount(4)
        ->and(app(GetPlans::class)()->first())->toHaveProperty('id');
});

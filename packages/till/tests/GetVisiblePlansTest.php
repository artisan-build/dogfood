<?php

use ArtisanBuild\Till\Actions\GetVisiblePlans;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

it('hides the free plan by default', function (): void {
    expect(app(GetVisiblePlans::class)())->toBeInstanceOf(Collection::class)->toHaveCount(3);
});



<?php

use ArtisanBuild\Till\Actions\GetVisiblePlans;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

it('hides the free plan by default', function (): void {
    expect(app(GetVisiblePlans::class)())->toBeInstanceOf(Collection::class)->toHaveCount(2);
});

it('shows the free plan when set in config', function (): void {
    Config::set('till.show_free_plan', true);
    expect(app(GetVisiblePlans::class)())->toBeInstanceOf(Collection::class)->toHaveCount(3);
});

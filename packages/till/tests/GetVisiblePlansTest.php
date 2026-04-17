<?php

declare(strict_types=1);

use ArtisanBuild\Till\Actions\GetVisiblePlans;
use Illuminate\Support\Collection;

it('hides the free plan by default', function (): void {
    expect(resolve(GetVisiblePlans::class)())->toBeInstanceOf(Collection::class)->toHaveCount(3);
});

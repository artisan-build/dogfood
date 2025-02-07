<?php

use ArtisanBuild\Till\Actions\GetPlanById;
use ArtisanBuild\Till\Enums\TestPlans;
use ArtisanBuild\Till\Plans\SoloPlan;
use Illuminate\Support\ItemNotFoundException;

it('gets a plan if the passed id exists', function (): void {
    expect(app(GetPlanById::class)(TestPlans::Solo->value))->toBeInstanceOf(SoloPlan::class);
});

it('throws if no plan exists with the passed id', function (): void {
    app(GetPlanById::class)(123);
})->throws(ItemNotFoundException::class);

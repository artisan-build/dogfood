<?php

use ArtisanBuild\Till\Actions\GetPlanById;
use ArtisanBuild\Till\Enums\TestPlans;
use Illuminate\Support\ItemNotFoundException;

it('gets a plan if the passed id exists', function (): void {
    expect(app(GetPlanById::class)(TestPlans::Unsubscribed->value))->toBeInstanceOf(\ArtisanBuild\Till\SubscriptionPlans\UnsubscribedPlan::class);
});

it('throws if no plan exists with the passed id', function (): void {
    app(GetPlanById::class)('bug-bug-bug');
})->throws(ItemNotFoundException::class);

<?php

use ArtisanBuild\Till\Enums\TestPlans;
use ArtisanBuild\Till\Events\SubscriptionStarted;
use ArtisanBuild\Till\Models\TillUser;
use ArtisanBuild\Till\Plans\Abilities\AddSeats;
use ArtisanBuild\Till\States\SubscriberState;
use Illuminate\Support\Facades\Cache;

describe('subscription started', function (): void {

    afterEach(fn () => Cache::purge());

    it('creates a state for the subscription', function (): void {
        test()->actingAs(TillUser::find(1));
        SubscriptionStarted::commit(
            subscriber_id: 1,
            plan_id: TestPlans::Solo->value
        );
        expect(SubscriberState::load(1)->plan_id)->toBe(TestPlans::Solo->value);
    });

    it('caches the correct abilities', function (): void {
        test()->actingAs(TillUser::find(1));
        expect(Cache::get('subscription-1'))->toBeNull();
        SubscriptionStarted::commit(
            subscriber_id: 1,
            plan_id: TestPlans::Solo->value
        );

        expect(Cache::get('subscription-1'))
            ->toBeArray()
            ->toHaveCount(1)
            ->toHaveKey(AddSeats::class)
            ->and(Cache::get('subscription-1')[AddSeats::class])->toBeFalse();

    });
});

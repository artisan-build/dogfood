<?php

namespace ArtisanBuild\Till\Plans;

use ArtisanBuild\Till\Contracts\PlanInterface;
use ArtisanBuild\Till\Enums\Currencies;
use ArtisanBuild\Till\Enums\PaymentProcessors;
use ArtisanBuild\Till\Traits\IsPricingPlan;
use ArtisanBuild\Till\Plans\Abilities\AddSeats;

#[\ArtisanBuild\Till\Attributes\TeamPlan]
class TeamPlan implements PlanInterface
{
    use IsPricingPlan;

    public int $id = 277786403491160064;

    public PaymentProcessors $processor = PaymentProcessors::Stripe;
    public Currencies $currency = Currencies::USD;

    public string $processor_price = 'team';
    public string $processor_sandbox_price = 'team_test';

    public array $price = [
        'month' => 10,
        'year' => 100,
        'life' => null,
    ];

    public array $badge = [
        'size' => 'sm',
        'variant' => '',
        'color' => 'lime',
        'text' => 'Most Popular',
        'icon' => 'user-group',
    ];

    public string $heading = 'Startup';
    public string $subheading = 'A great value for your growing team';

    public array $features = [
        ['text' => 'Up to 5 Users', 'icon' => 'user-group'],
        ['text' => '500 Queries / Day', 'icon' => null],
        ['text' => 'Email Support', 'icon' => null],
    ];

    public array $can = [
        [AddSeats::class, ['limit' => 1]],

    ];

}

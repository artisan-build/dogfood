<?php

namespace ArtisanBuild\Till\Plans;

use ArtisanBuild\Till\Attributes\TeamPlan;
use ArtisanBuild\Till\Contracts\PlanInterface;
use ArtisanBuild\Till\Enums\Currencies;
use ArtisanBuild\Till\Enums\PaymentProcessors;
use ArtisanBuild\Till\Enums\TestPlans;
use ArtisanBuild\Till\Plans\Abilities\AddSeats;
use ArtisanBuild\Till\Traits\IsPricingPlan;

#[TeamPlan]
class StartupPlan implements PlanInterface
{
    use IsPricingPlan;

    public int $id = TestPlans::Startup->value;

    public bool $current = false;

    public PaymentProcessors $processor = PaymentProcessors::Stripe;

    public Currencies $currency = Currencies::USD;

    public array $prices = [
        'month' => [
            'price' => 10,
            'live' => 'startup-month',
            'test' => 'startup-month-test',
        ],
        'year' => [
            'price' => 100,
            'live' => 'startup-year',
            'test' => 'startup-year-test',
        ],
        'life' => [
            'price' => null,
            'live' => null,
            'test' => null,
        ],
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

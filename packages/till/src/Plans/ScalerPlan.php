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
class ScalerPlan implements PlanInterface
{
    use IsPricingPlan;

    public bool $current = false;

    public int $id = TestPlans::Scaler->value;

    public PaymentProcessors $processor = PaymentProcessors::Stripe;

    public Currencies $currency = Currencies::USD;

    public array $prices = [
        'month' => [
            'price' => 50,
            'live' => 'scaler-month',
            'test' => 'scaler-month-test',
        ],
        'year' => [
            'price' => 500,
            'live' => 'scaler-year',
            'test' => 'scaler-year-test',
        ],
        'life' => [
            'price' => null,
            'live' => null,
            'test' => null,
        ],
    ];

    public array $badge = [
        'size' => '',
        'variant' => '',
        'color' => '',
        'text' => '',
        'icon' => '',
    ];

    public string $heading = 'Scaler';

    public string $subheading = 'Everything you need to run your business';

    public array $features = [
        ['text' => 'Unlimited Users', 'icon' => null],
        ['text' => 'Unlimited Queries', 'icon' => null],
        ['text' => 'Phone Support', 'icon' => null],
    ];

    public array $can = [
        [AddSeats::class, ['limit' => 1]],

    ];
}

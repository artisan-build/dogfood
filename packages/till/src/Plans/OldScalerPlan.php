<?php

namespace ArtisanBuild\Till\Plans;

use ArtisanBuild\Till\Attributes\TeamPlan;
use ArtisanBuild\Till\Attributes\UnavailablePlan;
use ArtisanBuild\Till\Enums\Currencies;
use ArtisanBuild\Till\Enums\PaymentProcessors;
use ArtisanBuild\Till\Enums\TestPlans;
use ArtisanBuild\Till\Plans\Abilities\AddSeats;
use ArtisanBuild\Till\Traits\IsPricingPlan;

#[TeamPlan]
#[UnavailablePlan]
class OldScalerPlan
{
    use IsPricingPlan;

    public int $id = TestPlans::OldScaler->value;

    public bool $current = false;

    public PaymentProcessors $processor = PaymentProcessors::Stripe;

    public Currencies $currency = Currencies::USD;

    public array $prices = [
        'month' => [
            'price' => 40,
            'live' => 'scaler-old-month',
            'test' => 'scaler-old-month-test',
        ],
        'year' => [
            'price' => 400,
            'live' => 'scaler-old-year',
            'test' => 'scaler-old-year-test',
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

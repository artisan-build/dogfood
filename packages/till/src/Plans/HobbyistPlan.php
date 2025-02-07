<?php

namespace ArtisanBuild\Till\Plans;

use ArtisanBuild\Till\Attributes\DefaultPlan;
use ArtisanBuild\Till\Attributes\IndividualPlan;
use ArtisanBuild\Till\Contracts\PlanInterface;
use ArtisanBuild\Till\Enums\Currencies;
use ArtisanBuild\Till\Enums\PaymentProcessors;
use ArtisanBuild\Till\Enums\TestPlans;
use ArtisanBuild\Till\Plans\Abilities\AddSeats;
use ArtisanBuild\Till\Traits\IsPricingPlan;

#[DefaultPlan]
#[IndividualPlan]
class HobbyistPlan implements PlanInterface
{
    use IsPricingPlan;

    public int $id = TestPlans::Hobbyist->value;

    public bool $current = false;

    public PaymentProcessors $processor = PaymentProcessors::Stripe;

    public Currencies $currency = Currencies::USD;

    public array $prices = [
        'month' => [
            'price' => 0,
            'live' => 'hobbyist-month',
            'test' => 'hobbyist-month-test',
        ],
        'year' => [
            'price' => 0,
            'live' => 'hobbyist-year',
            'test' => 'hobbyist-year-test',
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

    public string $heading = 'Solo';

    public string $subheading = 'Everything the indie hacker needs';

    public array $features = [
        ['text' => 'One User', 'icon' => null],
        ['text' => '50 Queries / Day', 'icon' => null],
        ['text' => 'Email Support', 'icon' => null],
    ];

    public array $can = [
        [AddSeats::class, ['limit' => 1]],

    ];
}

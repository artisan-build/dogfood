<?php

namespace App\Plans;

use App\Plans\Abilities\AddSeats;
use ArtisanBuild\Till\Attributes\DefaultPlan;
use ArtisanBuild\Till\Enums\Currencies;
use ArtisanBuild\Till\Enums\PaymentProcessors;
use ArtisanBuild\Till\Traits\IsPricingPlan;

#[DefaultPlan]
class SoloPlan
{
    use IsPricingPlan;

    public int $id = 277786259502444544;

    public PaymentProcessors $processor = PaymentProcessors::Stripe;
    public Currencies $currency = Currencies::USD;

    public string $processor_price = 'solo';
    public string $processor_sandbox_price = 'solo_test';

    public array $price = [
        'month' => 0,
        'year' => 0,
        'life' => null,
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

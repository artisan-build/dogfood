<?php

namespace App\Plans;

use App\Plans\Abilities\AddSeats;
use ArtisanBuild\Till\Enums\Currencies;
use ArtisanBuild\Till\Enums\PaymentProcessors;
use ArtisanBuild\Till\Traits\IsPricingPlan;

class ScalerPlan
{
    use IsPricingPlan;

    public int $id = 277786470450941952;

    public PaymentProcessors $processor = PaymentProcessors::Stripe;
    public Currencies $currency = Currencies::USD;

    public string $processor_price = 'scaler';
    public string $processor_sandbox_price = 'scaler_test';

    public array $price = [
        'month' => 50,
        'year' => 500,
        'life' => null,
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

<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Permissions;

use ArtisanBuild\Hallway\Payment\Enums\PaymentStates;
use Illuminate\Support\Facades\Context;

class IsPremiumMember
{
    public function __invoke()
    {
        return in_array(Context::get('active_member')->payment_state, [
            PaymentStates::Premium,
            PaymentStates::Exempt,
            PaymentStates::GracePeriod,
        ], true);
    }
}

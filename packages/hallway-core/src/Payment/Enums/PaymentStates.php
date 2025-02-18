<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Payment\Enums;

enum PaymentStates: int
{
    case Free = 0; // User has not paid
    case Cancelled = 1; // User was paid but has cancelled
    case Suspended = 2; // User owes and is suspended until payment received

    case Premium = 11; // User has paid and is current
    case GracePeriod = 12; // User owes and is past due, but on grace period
    case Trial = 13; // User owes and is past due, but on grace period
    case Exempt = 20; // Exempt from any payment-related restrictions




    public function isCurrentlyPremium(): bool
    {
        return in_array($this, [
            self::Premium,
            self::GracePeriod,
            self::Trial,
            self::Exempt,
        ], true);
    }
}

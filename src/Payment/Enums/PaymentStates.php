<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Payment\Enums;

enum PaymentStates: int
{
    case Free = 0; // User has not paid
    case Premium = 1; // User has paid and is current
    case Exempt = 2; // Exempt from any payment-related restrictions
    case Cancelled = 3; // User was paid but has cancelled
    case GracePeriod = 4; // User owes and is past due, but on grace period
    case Suspended = 5; // User owes and is suspended until payment received

    public function can(AbilitiesByPaymentStatus $ability): bool
    {
        return in_array($ability, $this->abilities(), true);
    }

    public function abilities(): array
    {
        return match ($this) {
            self::Free, self::Cancelled, self::Suspended => [
                AbilitiesByPaymentStatus::PostInFreeChannels,
                AbilitiesByPaymentStatus::ReadFreeChannels,
            ],
            self::GracePeriod, self::Premium, self::Exempt => [
                AbilitiesByPaymentStatus::PostInFreeChannels,
                AbilitiesByPaymentStatus::ReadFreeChannels,
                AbilitiesByPaymentStatus::PostInPremiumChannels,
                AbilitiesByPaymentStatus::ReadPremiumChannels,
            ],
        };
    }
}

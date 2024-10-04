<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Payment\Enums;

enum PaymentStates: string
{
    case Free = 'Free'; // User has not paid
    case Paid = 'Paid'; // User has paid and is current
    case Exempt = 'Exempt'; // Exempt from any payment-related restrictions
    case Cancelled = 'Cancelled'; // User was paid but has cancelled
    case GracePeriod = 'GracePeriod'; // User owes and is past due, but on grace period
    case Suspended = 'Suspended'; // User owes and is suspended until payment received

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
            self::GracePeriod, self::Paid, self::Exempt => [
                AbilitiesByPaymentStatus::PostInFreeChannels,
                AbilitiesByPaymentStatus::ReadFreeChannels,
                AbilitiesByPaymentStatus::PostInPremiumChannels,
                AbilitiesByPaymentStatus::ReadPremiumChannels,
            ],
        };
    }
}

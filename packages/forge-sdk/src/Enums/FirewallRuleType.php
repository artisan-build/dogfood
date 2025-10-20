<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Enums;

use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

/**
 * Firewall rule types in Laravel Forge.
 *
 * Verified against: /docs/api-reference/firewall-rules (Create Firewall Rule endpoint)
 */
enum FirewallRuleType: string
{
    case ALLOW = 'allow';
    case DENY = 'deny';

    /**
     * Validate a firewall rule type value and return the enum case.
     *
     * @throws ValidationException If the value is not a valid firewall rule type
     */
    public static function validate(string $value): self
    {
        return self::tryFrom($value)
            ?? throw new ValidationException("Invalid firewall rule type: {$value}. Valid values are: allow, deny");
    }
}

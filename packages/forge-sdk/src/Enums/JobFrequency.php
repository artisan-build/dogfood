<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Enums;

use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

/**
 * Job frequency options for scheduled jobs in Laravel Forge.
 *
 * Verified against: /docs/api-reference/scheduled-jobs (Create Scheduled Job endpoint)
 */
enum JobFrequency: string
{
    case MINUTELY = 'minutely';
    case HOURLY = 'hourly';
    case NIGHTLY = 'nightly';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
    case REBOOT = 'reboot';
    case CUSTOM = 'custom';

    /**
     * Validate a job frequency value and return the enum case.
     *
     * @throws ValidationException If the value is not a valid job frequency
     */
    public static function validate(string $value): self
    {
        return self::tryFrom($value)
            ?? throw new ValidationException("Invalid job frequency: {$value}. Valid values are: minutely, hourly, nightly, weekly, monthly, reboot, custom");
    }
}

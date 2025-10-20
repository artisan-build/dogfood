<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Enums;

use ArtisanBuild\ForgeClient\Exceptions\ValidationException;

/**
 * Monitor types available in Laravel Forge.
 *
 * Verified against: /docs/api-reference/monitors (Create Monitor endpoint)
 */
enum MonitorType: string
{
    case CPU_LOAD = 'cpu_load';
    case USED_DISK_SPACE = 'used_disk_space';
    case MEMORY_USAGE = 'memory_usage';

    /**
     * Validate a monitor type value and return the enum case.
     *
     * @throws ValidationException If the value is not a valid monitor type
     */
    public static function validate(string $value): self
    {
        return self::tryFrom($value)
            ?? throw new ValidationException("Invalid monitor type: {$value}. Valid values are: cpu_load, used_disk_space, memory_usage");
    }
}

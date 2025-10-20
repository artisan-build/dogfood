<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Exceptions;

/**
 * Exception thrown when request validation fails before sending to the API.
 *
 * Provides detailed information about validation failures to help developers
 * identify and fix issues before making API requests.
 */
class ValidationException extends ForgeException
{
    /**
     * Create a validation exception for an invalid parameter value.
     */
    public static function invalidParameter(string $parameter, mixed $value, string $reason): static
    {
        $valueString = is_string($value) ? "'{$value}'" : json_encode($value);
        $message = "Invalid parameter '{$parameter}': {$valueString}. {$reason}";

        return static::make($message, [
            'parameter' => $parameter,
            'value' => $value,
            'reason' => $reason,
        ]);
    }

    /**
     * Create a validation exception for a missing required parameter.
     */
    public static function missingParameter(string $parameter): static
    {
        $message = "Missing required parameter: '{$parameter}'";

        return static::make($message, [
            'parameter' => $parameter,
        ]);
    }

    /**
     * Create a validation exception for an invalid enum value.
     *
     * @param  array<int, string>  $allowedValues
     */
    public static function invalidEnum(string $parameter, string $value, array $allowedValues): static
    {
        $allowed = implode(', ', $allowedValues);
        $message = "Invalid value '{$value}' for parameter '{$parameter}'. Allowed values: {$allowed}";

        return static::make($message, [
            'parameter' => $parameter,
            'value' => $value,
            'allowed_values' => $allowedValues,
        ]);
    }
}

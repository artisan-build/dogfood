<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Console\Concerns;

use Illuminate\Support\Facades\Log;

trait LogsForgeOperations
{
    protected function logOperation(string $operation, array $context = [], string $level = 'info'): void
    {
        $channel = config('forge-sdk.logging.channel') ?: config('logging.default');

        Log::channel($channel)->log($level, "Forge SDK: {$operation}", array_merge([
            'command' => $this->getName(),
            'timestamp' => now()->toIso8601String(),
        ], $this->sanitizeContext($context)));
    }

    protected function logSuccess(string $operation, array $context = []): void
    {
        $this->logOperation("{$operation} - Success", $context, 'info');
    }

    protected function logError(string $operation, string $error, array $context = []): void
    {
        $this->logOperation("{$operation} - Error: {$error}", $context, 'error');
    }

    protected function sanitizeContext(array $context): array
    {
        $sanitized = $context;

        // Remove or mask sensitive data
        $sensitiveKeys = ['api_token', 'token', 'password', 'secret', 'key'];

        foreach ($sensitiveKeys as $key) {
            if (isset($sanitized[$key])) {
                $sanitized[$key] = '***REDACTED***';
            }
        }

        return $sanitized;
    }
}

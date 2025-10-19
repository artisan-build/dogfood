<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Console\Concerns;

trait PerformsDestructiveForgeOperations
{
    protected function requiresConfirmation(): bool
    {
        return ! $this->option('dangerously-skip-confirmation');
    }

    protected function confirmOperation(string $message): bool
    {
        if (! $this->requiresConfirmation()) {
            $this->logOperation('Confirmation bypassed with --dangerously-skip-confirmation', [], 'warning');

            return true;
        }

        return $this->confirm($message);
    }
}

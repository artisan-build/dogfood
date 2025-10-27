<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeClient\Concerns;

trait HandlesOpencodeErrors
{
    public ?string $error = null;

    public ?string $successMessage = null;

    /**
     * Set error message.
     */
    protected function setError(string $message): void
    {
        $this->error = $message;
        $this->successMessage = null;
    }

    /**
     * Set success message.
     */
    protected function setSuccess(string $message): void
    {
        $this->successMessage = $message;
        $this->error = null;
    }

    /**
     * Clear all messages.
     */
    protected function clearMessages(): void
    {
        $this->error = null;
        $this->successMessage = null;
    }

    /**
     * Check if response has error.
     */
    protected function hasError(array $response): bool
    {
        return isset($response['error']) || isset($response['message']);
    }

    /**
     * Get error message from response.
     */
    protected function getErrorMessage(array $response): string
    {
        $error = $response['error'] ?? $response['message'] ?? 'An unknown error occurred';

        // If error is an array, convert it to a string
        if (is_array($error)) {
            return json_encode($error);
        }

        return (string) $error;
    }

    /**
     * Handle OpenCode response with automatic error handling.
     */
    protected function handleResponse(array $response, ?string $successMessage = null): bool
    {
        if ($this->hasError($response)) {
            $this->setError($this->getErrorMessage($response));

            return false;
        }

        if ($successMessage) {
            $this->setSuccess($successMessage);
        }

        return true;
    }

    /**
     * Show connection error.
     */
    protected function showConnectionError(): void
    {
        $this->setError(
            'Could not connect to OpenCode server. Make sure OpenCode is running at '.$this->serverUrl
        );
    }
}

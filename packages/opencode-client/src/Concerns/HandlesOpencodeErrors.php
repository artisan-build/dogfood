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
        // Only treat as error if 'error' key exists
        // OR if 'message' exists WITHOUT any expected data keys
        if (isset($response['error'])) {
            return true;
        }

        // If there's a message but no data keys, it might be an error
        if (isset($response['message']) && $this->isErrorMessage($response)) {
            return true;
        }

        return false;
    }

    /**
     * Check if a response with 'message' is actually an error.
     */
    protected function isErrorMessage(array $response): bool
    {
        // List of keys that indicate successful data responses
        $dataKeys = [
            'files',       // File list responses
            'content',     // File read responses
            'sessions',    // Session list responses
            'session',     // Session detail responses
            'messages',    // Message list responses
            'results',     // Search results
            'projects',    // Project list responses
            'project',     // Project detail responses
            'statuses',    // File status responses
        ];

        return array_all($dataKeys, fn ($key) => ! isset($response[$key]));
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

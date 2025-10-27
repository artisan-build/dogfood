<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeClient\Livewire;

use ArtisanBuild\OpencodeClient\Concerns\HandlesOpencodeErrors;
use ArtisanBuild\OpencodeClient\Services\OpencodeService;
use Livewire\Component;

class OpencodeRemote extends Component
{
    use HandlesOpencodeErrors;

    /**
     * OpenCode server URL.
     */
    public string $serverUrl = 'http://127.0.0.1:64415';

    /**
     * Whether TUI is connected.
     */
    public bool $tuiConnected = false;

    /**
     * TUI status information.
     */
    public ?string $tuiStatus = null;

    /**
     * Prompt text to submit.
     */
    public string $promptText = '';

    /**
     * Text to append to prompt.
     */
    public string $appendText = '';

    /**
     * Loading state for submit prompt.
     */
    public bool $isSubmittingPrompt = false;

    /**
     * Loading state for append prompt.
     */
    public bool $isAppendingPrompt = false;

    /**
     * Loading state for clear prompt.
     */
    public bool $isClearingPrompt = false;

    /**
     * Success message.
     */
    public ?string $success = null;

    /**
     * OpencodeService instance.
     */
    protected OpencodeService $opencode;

    /**
     * Boot component dependencies.
     */
    public function boot(): void
    {
        $this->opencode = app(OpencodeService::class);
    }

    /**
     * Initialize component.
     */
    public function mount(): void
    {
        $this->checkTuiConnection();
    }

    /**
     * Check TUI connection status.
     */
    public function checkTuiConnection(): void
    {
        $this->clearMessages();

        $response = $this->opencode->getServerStatus();

        // Check if the response indicates success (no error)
        if (! isset($response['error'])) {
            $this->tuiConnected = true;
            // If the response has a 'status' field, use it; otherwise default to 'running'
            $this->tuiStatus = $response['status'] ?? 'running';
        } else {
            $this->tuiConnected = false;
            $this->tuiStatus = null;
            // Set error message if there was an error
            if (isset($response['error'])) {
                $this->error = $response['message'] ?? $response['error'];
            }
        }
    }

    /**
     * Submit prompt to TUI.
     */
    public function submitPrompt(): void
    {
        $this->clearMessages();
        $this->success = null;

        if (empty(trim($this->promptText))) {
            $this->error = 'Prompt cannot be empty';

            return;
        }

        $this->isSubmittingPrompt = true;

        $response = $this->opencode->submitTuiPrompt($this->promptText);

        if ($this->handleResponse($response)) {
            $this->success = $response['message'] ?? 'Prompt submitted successfully';
            $this->promptText = '';
        }

        $this->isSubmittingPrompt = false;
    }

    /**
     * Append text to TUI prompt.
     */
    public function appendPrompt(): void
    {
        $this->clearMessages();
        $this->success = null;

        if (empty(trim($this->appendText))) {
            $this->error = 'Text to append cannot be empty';

            return;
        }

        $this->isAppendingPrompt = true;

        $response = $this->opencode->appendTuiPrompt($this->appendText);

        if ($this->handleResponse($response)) {
            $this->success = $response['message'] ?? 'Text appended successfully';
            $this->appendText = '';
        }

        $this->isAppendingPrompt = false;
    }

    /**
     * Clear TUI prompt.
     */
    public function clearPrompt(): void
    {
        $this->clearMessages();
        $this->success = null;

        $this->isClearingPrompt = true;

        $response = $this->opencode->clearTuiPrompt();

        if ($this->handleResponse($response)) {
            $this->success = $response['message'] ?? 'Prompt cleared successfully';
        }

        $this->isClearingPrompt = false;
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('opencode-client::livewire.opencode-remote')
            ->layout('layouts.app');
    }
}

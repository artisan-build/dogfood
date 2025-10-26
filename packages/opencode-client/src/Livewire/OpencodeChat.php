<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeClient\Livewire;

use ArtisanBuild\OpencodeClient\Concerns\HandlesOpencodeErrors;
use ArtisanBuild\OpencodeClient\Services\OpencodeService;
use Livewire\Component;

class OpencodeChat extends Component
{
    use HandlesOpencodeErrors;

    /**
     * OpenCode server URL.
     */
    public string $serverUrl = 'http://127.0.0.1:64415';

    /**
     * Current session ID.
     */
    public ?string $currentSessionId = null;

    /**
     * List of all sessions.
     */
    public array $sessions = [];

    /**
     * Current message input.
     */
    public string $messageInput = '';

    /**
     * Array of messages in the current session.
     */
    public array $messages = [];

    /**
     * Whether the component is currently connecting.
     */
    public bool $connecting = false;

    /**
     * Whether the component is currently sending a message.
     */
    public bool $sending = false;

    /**
     * Whether the component is currently loading sessions.
     */
    public bool $loadingSessions = false;

    /**
     * Whether the tree visualization modal is open.
     */
    public bool $showTreeModal = false;

    /**
     * Whether the diff viewer modal is open.
     */
    public bool $showDiffModal = false;

    /**
     * Current message ID for diff viewing.
     */
    public ?string $currentMessageId = null;

    /**
     * Diff data for current message.
     */
    public ?array $diffData = null;

    /**
     * Session summary.
     */
    public ?string $sessionSummary = null;

    /**
     * Share URL for session.
     */
    public ?string $shareUrl = null;

    /**
     * Whether the todo panel is open.
     */
    public bool $showTodoPanel = false;

    /**
     * Array of todos for the current session.
     */
    public array $todos = [];

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
        $this->loadSessions();
    }

    /**
     * Load all sessions.
     */
    public function loadSessions(): void
    {
        $this->loadingSessions = true;

        $response = $this->opencode->listSessions();

        if ($this->handleResponse($response)) {
            $this->sessions = $response;
        }

        $this->loadingSessions = false;
    }

    /**
     * Create a new session.
     */
    public function createNewSession(): void
    {
        $this->clearMessages();

        $response = $this->opencode->createSession();

        if ($this->handleResponse($response, 'Session created successfully')) {
            $this->currentSessionId = $response['id'] ?? null;

            // Reload sessions list to include the new session
            $this->loadSessions();
        }
    }

    /**
     * Switch to a different session.
     */
    public function switchSession(string $sessionId): void
    {
        $this->clearMessages();

        // Get session details
        $response = $this->opencode->getSession($sessionId);

        if (! $this->handleResponse($response)) {
            return;
        }

        $this->currentSessionId = $sessionId;
        $this->messages = [];

        // Load messages for this session
        $this->loadMessages();
    }

    /**
     * Load messages for current session.
     */
    protected function loadMessages(): void
    {
        if (! $this->currentSessionId) {
            return;
        }

        $response = $this->opencode->getMessages($this->currentSessionId);

        if ($this->handleResponse($response)) {
            // Transform messages from API format (with 'parts') to display format (with 'content')
            $this->messages = array_map(function ($message) {
                $content = '';

                if (isset($message['parts']) && is_array($message['parts'])) {
                    foreach ($message['parts'] as $part) {
                        if (isset($part['type']) && $part['type'] === 'text' && isset($part['text'])) {
                            $content = $part['text'];
                            break;
                        }
                    }
                }

                return [
                    'role' => $message['role'] ?? 'unknown',
                    'content' => $content,
                    'timestamp' => $message['timestamp'] ?? $message['created_at'] ?? null,
                    'id' => $message['id'] ?? null,
                    'reverted' => $message['reverted'] ?? false,
                ];
            }, $response);
        }
    }

    /**
     * Delete a session.
     */
    public function deleteSession(string $sessionId): void
    {
        $response = $this->opencode->deleteSession($sessionId);

        if ($this->handleResponse($response, 'Session deleted successfully')) {
            // If we deleted the current session, clear it
            if ($this->currentSessionId === $sessionId) {
                $this->currentSessionId = null;
                $this->messages = [];
            }

            // Reload sessions list
            $this->loadSessions();
        }
    }

    /**
     * Rename a session.
     */
    public function renameSession(string $sessionId, string $newName): void
    {
        // Validate name
        if (empty(trim($newName))) {
            $this->setError('Session name cannot be empty');

            return;
        }

        $response = $this->opencode->updateSession($sessionId, ['name' => $newName]);

        if ($this->handleResponse($response, 'Session renamed successfully')) {
            // Reload sessions list to show updated name
            $this->loadSessions();
        }
    }

    /**
     * Fork a session from a specific message.
     */
    public function forkSession(string $messageId): void
    {
        $this->clearMessages();

        if (! $this->currentSessionId) {
            $this->setError('No active session to fork from');

            return;
        }

        $response = $this->opencode->forkSession($this->currentSessionId, $messageId);

        if ($this->handleResponse($response, 'Session forked successfully')) {
            // Switch to the new forked session
            $this->currentSessionId = $response['id'] ?? null;

            // Reload sessions list to show the new fork
            $this->loadSessions();
        }
    }

    /**
     * Open the tree visualization modal.
     */
    public function openTreeModal(): void
    {
        $this->showTreeModal = true;
    }

    /**
     * Close the tree visualization modal.
     */
    public function closeTreeModal(): void
    {
        $this->showTreeModal = false;
    }

    /**
     * Navigate to a session from the tree visualization.
     */
    public function navigateToSessionFromTree(string $sessionId): void
    {
        $this->switchSession($sessionId);
        $this->closeTreeModal();
    }

    /**
     * Get tree data structure for visualization.
     */
    public function getTreeDataProperty(): array
    {
        $nodes = [];
        $edges = [];

        foreach ($this->sessions as $session) {
            $nodes[] = [
                'id' => $session['id'],
                'label' => $session['name'] ?? 'Session '.$session['id'],
                'color' => $session['id'] === $this->currentSessionId ? '#3b82f6' : '#6b7280',
            ];

            if (isset($session['parent_id']) && $session['parent_id']) {
                $edges[] = [
                    'from' => $session['parent_id'],
                    'to' => $session['id'],
                ];
            }
        }

        return [
            'nodes' => $nodes,
            'edges' => $edges,
        ];
    }

    /**
     * Open the diff viewer modal for a message.
     */
    public function openDiffModal(string $messageId): void
    {
        $this->clearMessages();

        if (! $this->currentSessionId) {
            $this->setError('No active session');

            return;
        }

        $this->currentMessageId = $messageId;

        $response = $this->opencode->getDiff($this->currentSessionId, $messageId);

        if ($this->handleResponse($response)) {
            $this->diffData = $response;
            $this->showDiffModal = true;
        }
    }

    /**
     * Close the diff viewer modal.
     */
    public function closeDiffModal(): void
    {
        $this->showDiffModal = false;
        $this->currentMessageId = null;
        $this->diffData = null;
    }

    /**
     * Revert a message.
     */
    public function revertMessage(string $messageId): void
    {
        $this->clearMessages();

        if (! $this->currentSessionId) {
            $this->setError('No active session');

            return;
        }

        $response = $this->opencode->revertMessage($this->currentSessionId, $messageId);

        if ($this->handleResponse($response, 'Message reverted successfully')) {
            $this->loadMessages();
        }
    }

    /**
     * Unrevert a message.
     */
    public function unrevertMessage(string $messageId): void
    {
        $this->clearMessages();

        if (! $this->currentSessionId) {
            $this->setError('No active session');

            return;
        }

        $response = $this->opencode->unrevertMessage($this->currentSessionId, $messageId);

        if ($this->handleResponse($response, 'Message unreverted successfully')) {
            $this->loadMessages();
        }
    }

    /**
     * Abort the current session.
     */
    public function abortSession(): void
    {
        $this->clearMessages();

        if (! $this->currentSessionId) {
            $this->setError('No active session');

            return;
        }

        $response = $this->opencode->abortSession($this->currentSessionId);

        if ($this->handleResponse($response, 'Session aborted successfully')) {
            // Session aborted
        }
    }

    /**
     * Summarize the current session.
     */
    public function summarizeSession(): void
    {
        $this->clearMessages();

        if (! $this->currentSessionId) {
            $this->setError('No active session');

            return;
        }

        $response = $this->opencode->summarizeSession($this->currentSessionId);

        if ($this->handleResponse($response)) {
            $this->sessionSummary = $response['summary'] ?? 'No summary available';
        }
    }

    /**
     * Share the current session.
     */
    public function shareSession(): void
    {
        $this->clearMessages();

        if (! $this->currentSessionId) {
            $this->setError('No active session');

            return;
        }

        $response = $this->opencode->shareSession($this->currentSessionId);

        if ($this->handleResponse($response, 'Session shared successfully')) {
            $this->shareUrl = $response['share_url'] ?? null;
        }
    }

    /**
     * Unshare the current session.
     */
    public function unshareSession(): void
    {
        $this->clearMessages();

        if (! $this->currentSessionId) {
            $this->setError('No active session');

            return;
        }

        $response = $this->opencode->unshareSession($this->currentSessionId);

        if ($this->handleResponse($response, 'Session unshared successfully')) {
            $this->shareUrl = null;
        }
    }

    /**
     * Open the todo panel.
     */
    public function openTodoPanel(): void
    {
        $this->showTodoPanel = true;
        $this->loadTodos();
    }

    /**
     * Close the todo panel.
     */
    public function closeTodoPanel(): void
    {
        $this->showTodoPanel = false;
    }

    /**
     * Load todos for the current session.
     */
    public function loadTodos(): void
    {
        $this->clearMessages();

        if (! $this->currentSessionId) {
            $this->setError('No active session');

            return;
        }

        $response = $this->opencode->getTodos($this->currentSessionId);

        if ($this->handleResponse($response)) {
            $this->todos = $response;
        }
    }

    /**
     * Toggle todo completion status.
     */
    public function toggleTodo(string $todoId): void
    {
        $this->clearMessages();

        if (! $this->currentSessionId) {
            $this->setError('No active session');

            return;
        }

        // Find the todo in the local array
        $todoIndex = null;
        foreach ($this->todos as $index => $todo) {
            if ($todo['id'] === $todoId) {
                $todoIndex = $index;
                break;
            }
        }

        if ($todoIndex === null) {
            $this->setError('Todo not found');

            return;
        }

        // Toggle the completed status
        $newStatus = ! $this->todos[$todoIndex]['completed'];

        // Call the API (assuming there's a toggleTodo endpoint)
        // For now, we'll just update locally since the API endpoint isn't clear
        $response = $this->opencode->getTodos($this->currentSessionId);

        if ($this->handleResponse($response)) {
            // Update the local todo
            $this->todos[$todoIndex]['completed'] = $newStatus;
        }
    }

    /**
     * Get total todo count.
     */
    public function getTodoCountProperty(): int
    {
        return count($this->todos);
    }

    /**
     * Get incomplete todo count.
     */
    public function getIncompleteTodoCountProperty(): int
    {
        return count(array_filter($this->todos, fn ($todo) => ! $todo['completed']));
    }

    /**
     * Send a message to OpenCode.
     */
    public function sendMessage(): void
    {
        $this->clearMessages();

        // Validate message input
        if (empty(trim($this->messageInput))) {
            $this->setError('Message cannot be empty');

            return;
        }

        // Check if we have a session
        if (! $this->currentSessionId) {
            $this->setError('No active session. Please create a session first.');

            return;
        }

        $this->sending = true;

        // Add user message to messages array
        $this->messages[] = [
            'role' => 'user',
            'content' => $this->messageInput,
            'timestamp' => now()->toIso8601String(),
            'id' => null,
        ];

        $userMessage = $this->messageInput;
        $this->messageInput = '';

        $response = $this->opencode->sendPrompt($this->currentSessionId, $userMessage);

        if ($this->handleResponse($response)) {
            // Extract the text from the parts array
            $content = 'No response';
            if (isset($response['parts']) && is_array($response['parts'])) {
                foreach ($response['parts'] as $part) {
                    if (isset($part['type']) && $part['type'] === 'text' && isset($part['text'])) {
                        $content = $part['text'];
                        break;
                    }
                }
            }

            // Add AI response to messages array
            $this->messages[] = [
                'role' => 'assistant',
                'content' => $content,
                'timestamp' => $response['timestamp'] ?? $response['created_at'] ?? now()->toIso8601String(),
                'id' => $response['id'] ?? null,
            ];
        }

        $this->sending = false;
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('opencode-client::livewire.opencode-chat')
            ->layout('layouts.app');
    }
}

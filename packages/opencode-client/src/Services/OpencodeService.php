<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeClient\Services;

use ArtisanBuild\OpencodeSdk\OpenCode\OpenCode;
use Saloon\Exceptions\Request\RequestException;

class OpencodeService
{
    public function __construct(
        protected string $baseUrl = 'http://127.0.0.1:64415'
    ) {}

    /**
     * Get OpenCode client instance.
     */
    public function client(): OpenCode
    {
        return new OpenCode(baseUrl: $this->baseUrl);
    }

    /**
     * Create a new session.
     */
    public function createSession(?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionCreate($directory)
        );
    }

    /**
     * List all sessions.
     */
    public function listSessions(?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionList($directory)
        );
    }

    /**
     * Get session details.
     */
    public function getSession(string $id, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionGet($id, $directory)
        );
    }

    /**
     * Update session.
     */
    public function updateSession(string $id, array $data, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionUpdate($id, $directory)
        );
    }

    /**
     * Delete session.
     */
    public function deleteSession(string $id, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionDelete($id, $directory)
        );
    }

    /**
     * Fork session at a specific message.
     */
    public function forkSession(string $id, string $messageId, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionFork($id, $directory)
        );
    }

    /**
     * Get session children (forked sessions).
     */
    public function getSessionChildren(string $id, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionChildren($id, $directory)
        );
    }

    /**
     * Get diff for a message.
     */
    public function getDiff(string $sessionId, string $messageId, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionDiff($sessionId, $directory)
        );
    }

    /**
     * Abort active session.
     */
    public function abortSession(string $id, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionAbort($id, $directory)
        );
    }

    /**
     * Summarize session.
     */
    public function summarizeSession(string $id, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionSummarize($id, $directory)
        );
    }

    /**
     * Share session.
     */
    public function shareSession(string $id, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionShare($id, $directory)
        );
    }

    /**
     * Unshare session.
     */
    public function unshareSession(string $id, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionUnshare($id, $directory)
        );
    }

    /**
     * Send prompt to session.
     */
    public function sendPrompt(string $id, string $prompt, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->send(new \ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionPrompt($id, $prompt, $directory))
        );
    }

    /**
     * Get session messages.
     */
    public function getMessages(string $id, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionMessages($id, $directory)
        );
    }

    /**
     * Get single message.
     */
    public function getMessage(string $sessionId, string $messageId, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionMessage($sessionId, $messageId, $directory)
        );
    }

    /**
     * Get session diff.
     */
    public function getSessionDiff(string $id, ?string $messageId = null, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionDiff($id, $directory, $messageId)
        );
    }

    /**
     * Revert message.
     */
    public function revertMessage(string $id, string $messageId, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionRevert($id, $directory)
        );
    }

    /**
     * Unrevert message.
     */
    public function unrevertMessage(string $id, string $messageId, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionUnrevert($id, $directory)
        );
    }

    /**
     * Get session todos.
     */
    public function getTodos(string $id, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionTodo($id, $directory)
        );
    }

    /**
     * Execute shell command in session.
     */
    public function executeShell(string $id, string $command, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->sessionShell($id, $directory)
        );
    }

    /**
     * List files in directory.
     */
    public function listFiles(string $path, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->fileList($directory, $path)
        );
    }

    /**
     * Read file contents.
     */
    public function readFile(string $path, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->fileRead($directory, $path)
        );
    }

    /**
     * Get file status (git status).
     */
    public function getFileStatus(?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->fileStatus($directory)
        );
    }

    /**
     * Search for text across files.
     */
    public function searchText(string $pattern, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->findText($directory, $pattern)
        );
    }

    /**
     * Search for files by name.
     */
    public function searchFiles(string $query, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->findFiles($directory, $query)
        );
    }

    /**
     * Search for code symbols.
     */
    public function searchSymbols(string $query, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->findSymbols($directory, $query)
        );
    }

    /**
     * List available projects.
     */
    public function listProjects(?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->projectList($directory)
        );
    }

    /**
     * Get current project.
     */
    public function getCurrentProject(?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->projectCurrent($directory)
        );
    }

    /**
     * Get path information.
     */
    public function getPath(string $path, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->pathGet($directory)
        );
    }

    /**
     * Submit prompt to TUI.
     */
    public function submitTuiPrompt(string $prompt, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->tuiSubmitPrompt($directory)
        );
    }

    /**
     * Append to TUI prompt.
     */
    public function appendTuiPrompt(string $text, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->tuiAppendPrompt($directory)
        );
    }

    /**
     * Clear TUI prompt.
     */
    public function clearTuiPrompt(?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->tuiClearPrompt($directory)
        );
    }

    /**
     * Show toast in TUI.
     */
    public function showTuiToast(string $message, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->tuiShowToast($directory)
        );
    }

    /**
     * Execute TUI command.
     */
    public function executeTuiCommand(string $command, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->tuiExecuteCommand($directory)
        );
    }

    /**
     * Open themes dialog in TUI.
     */
    public function openTuiThemes(?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->tuiOpenThemes($directory)
        );
    }

    /**
     * Open models dialog in TUI.
     */
    public function openTuiModels(?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->tuiOpenModels($directory)
        );
    }

    /**
     * Open help dialog in TUI.
     */
    public function openTuiHelp(?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->tuiOpenHelp($directory)
        );
    }

    /**
     * Open sessions dialog in TUI.
     */
    public function openTuiSessions(?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->tuiOpenSessions($directory)
        );
    }

    /**
     * Respond to a permission request (approve or deny).
     */
    public function respondToPermission(string $sessionId, string $permissionId, ?string $directory = null): array
    {
        return $this->handleRequest(fn() =>
            $this->client()->misc()->postSessionIdPermissionsPermissionId($sessionId, $permissionId, $directory)
        );
    }

    /**
     * Handle API request with error handling.
     */
    protected function handleRequest(callable $request): array
    {
        try {
            $response = $request();

            return $response->json();
        } catch (RequestException $e) {
            return [
                'error' => $e->getMessage(),
                'message' => $e->getResponse()?->json('message') ?? 'Unknown error',
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'message' => 'An unexpected error occurred',
            ];
        }
    }
}

<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeClient\Livewire;

use ArtisanBuild\OpencodeClient\Concerns\HandlesOpencodeErrors;
use ArtisanBuild\OpencodeClient\Services\OpencodeService;
use Livewire\Component;

class OpencodeExplorer extends Component
{
    use HandlesOpencodeErrors;

    /**
     * OpenCode server URL.
     */
    public string $serverUrl = 'http://127.0.0.1:64415';

    /**
     * Current directory path.
     */
    public string $currentPath = '/';

    /**
     * Array of files and directories in current path.
     */
    public array $files = [];

    /**
     * Array of expanded directory paths.
     */
    public array $expandedDirectories = [];

    /**
     * Currently viewed file path.
     */
    public ?string $currentFile = null;

    /**
     * Content of currently viewed file.
     */
    public ?string $fileContent = null;

    /**
     * Language of currently viewed file for syntax highlighting.
     */
    public ?string $fileLanguage = null;

    /**
     * File statuses from git (path => status).
     */
    public array $fileStatuses = [];

    /**
     * Search query string.
     */
    public string $searchQuery = '';

    /**
     * Current search mode (text, files, symbols).
     */
    public string $searchMode = 'text';

    /**
     * Search results.
     */
    public array $searchResults = [];

    /**
     * Whether to show search panel.
     */
    public bool $showSearch = false;

    /**
     * Available projects list.
     */
    public array $availableProjects = [];

    /**
     * Current project information.
     */
    public ?array $currentProject = null;

    /**
     * Whether to show project dropdown.
     */
    public bool $showProjectDropdown = false;

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
        $this->loadFiles('/');
    }

    /**
     * Load files for a given path.
     */
    public function loadFiles(string $path): void
    {
        $this->clearMessages();

        $response = $this->opencode->listFiles($path);

        if ($this->handleResponse($response)) {
            // API returns array of files directly, not wrapped in 'files' key
            $this->files = ! isset($response['error']) ? $response : [];
            $this->currentPath = $path;
        }
    }

    /**
     * Navigate to a directory.
     */
    public function navigateToDirectory(string $path): void
    {
        $this->loadFiles($path);
    }

    /**
     * Navigate to parent directory.
     */
    public function navigateToParent(): void
    {
        $parentPath = dirname($this->currentPath);

        // If we're at root, stay at root
        if ($parentPath === '.') {
            $parentPath = '/';
        }

        $this->loadFiles($parentPath);
    }

    /**
     * Toggle directory expansion.
     */
    public function toggleDirectory(string $path): void
    {
        if (in_array($path, $this->expandedDirectories)) {
            // Collapse directory
            $this->expandedDirectories = array_filter(
                $this->expandedDirectories,
                fn ($dir) => $dir !== $path
            );
        } else {
            // Expand directory
            $this->expandedDirectories[] = $path;
        }

        // Re-index array
        $this->expandedDirectories = array_values($this->expandedDirectories);
    }

    /**
     * Get directories from files array.
     */
    public function getDirectoriesProperty(): array
    {
        $directories = array_filter($this->files, fn ($item) => $item['type'] === 'directory');

        // Sort alphabetically (usort re-indexes the array)
        usort($directories, fn ($a, $b) => strcmp((string) $a['name'], (string) $b['name']));

        return $directories;
    }

    /**
     * Get regular files from files array.
     */
    public function getRegularFilesProperty(): array
    {
        $files = array_filter($this->files, fn ($item) => $item['type'] === 'file');

        // Sort alphabetically (usort re-indexes the array)
        usort($files, fn ($a, $b) => strcmp((string) $a['name'], (string) $b['name']));

        return $files;
    }

    /**
     * Get breadcrumb trail for current path.
     */
    public function getBreadcrumbsProperty(): array
    {
        if ($this->currentPath === '/') {
            return ['/'];
        }

        $parts = explode('/', trim($this->currentPath, '/'));
        $breadcrumbs = ['/'];

        $accumulated = '';
        foreach ($parts as $part) {
            $accumulated .= '/'.$part;
            $breadcrumbs[] = $accumulated;
        }

        return $breadcrumbs;
    }

    /**
     * View file contents.
     */
    public function viewFile(string $path): void
    {
        $this->clearMessages();

        $response = $this->opencode->readFile($path);

        if ($this->handleResponse($response)) {
            $this->currentFile = $path;
            $this->fileContent = $response['content'] ?? '';
            $this->fileLanguage = $response['language'] ?? $this->detectLanguage($path);
        }
    }

    /**
     * Close file viewer.
     */
    public function closeFileViewer(): void
    {
        $this->currentFile = null;
        $this->fileContent = null;
        $this->fileLanguage = null;
    }

    /**
     * Load git file statuses.
     */
    public function loadFileStatuses(): void
    {
        $this->clearMessages();

        $response = $this->opencode->getFileStatus();

        if ($this->handleResponse($response)) {
            // Convert array of status objects to path => status map
            $statuses = $response['statuses'] ?? [];
            $this->fileStatuses = [];

            foreach ($statuses as $status) {
                if (isset($status['path']) && isset($status['status'])) {
                    $this->fileStatuses[$status['path']] = $status['status'];
                }
            }
        }
    }

    /**
     * Search for text across files.
     */
    public function searchText(string $query): void
    {
        $this->clearMessages();
        $this->searchQuery = $query;
        $this->searchMode = 'text';
        $this->showSearch = true;

        $response = $this->opencode->searchText($query);

        if ($this->handleResponse($response)) {
            $this->searchResults = $response['results'] ?? [];
        }
    }

    /**
     * Search for files by name.
     */
    public function searchFiles(string $query): void
    {
        $this->clearMessages();
        $this->searchQuery = $query;
        $this->searchMode = 'files';
        $this->showSearch = true;

        $response = $this->opencode->searchFiles($query);

        if ($this->handleResponse($response)) {
            $this->searchResults = $response['results'] ?? [];
        }
    }

    /**
     * Search for code symbols.
     */
    public function searchSymbols(string $query): void
    {
        $this->clearMessages();
        $this->searchQuery = $query;
        $this->searchMode = 'symbols';
        $this->showSearch = true;

        $response = $this->opencode->searchSymbols($query);

        if ($this->handleResponse($response)) {
            $this->searchResults = $response['results'] ?? [];
        }
    }

    /**
     * Clear search results.
     */
    public function clearSearch(): void
    {
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->showSearch = false;
    }

    /**
     * Load list of available projects.
     */
    public function loadProjects(): void
    {
        $this->clearMessages();

        $response = $this->opencode->listProjects();

        if ($this->handleResponse($response)) {
            $this->availableProjects = $response['projects'] ?? [];
        }
    }

    /**
     * Load current project information.
     */
    public function loadCurrentProject(): void
    {
        $this->clearMessages();

        $response = $this->opencode->getCurrentProject();

        if ($this->handleResponse($response)) {
            $this->currentProject = $response['project'] ?? null;
        }
    }

    /**
     * Switch to a different project.
     */
    public function switchProject(string $projectPath): void
    {
        $this->clearMessages();

        // For now, we'll simulate the switch by updating the current project
        // and reloading the file tree at root
        // In a real implementation, this would call a project switch API

        // Find the project in available projects
        $project = collect($this->availableProjects)->firstWhere('path', $projectPath);

        if (! $project) {
            // If not in available projects, create a basic project structure
            $project = [
                'path' => $projectPath,
                'name' => basename($projectPath),
            ];
        }

        $this->currentProject = $project;
        $this->currentPath = '/';
        $this->loadFiles('/');
        $this->showProjectDropdown = false;
    }

    /**
     * Toggle project dropdown visibility.
     */
    public function toggleProjectDropdown(): void
    {
        $this->showProjectDropdown = ! $this->showProjectDropdown;
    }

    /**
     * Get line count of current file.
     */
    public function getLineCountProperty(): int
    {
        if (! $this->fileContent) {
            return 0;
        }

        return substr_count($this->fileContent, "\n") + 1;
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('opencode-client::livewire.opencode-explorer')
            ->layout('layouts.app');
    }

    /**
     * Detect language from file path.
     */
    protected function detectLanguage(string $path): string
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        $languageMap = [
            'php' => 'php',
            'js' => 'javascript',
            'jsx' => 'javascript',
            'ts' => 'typescript',
            'tsx' => 'typescript',
            'css' => 'css',
            'scss' => 'scss',
            'json' => 'json',
            'md' => 'markdown',
            'html' => 'html',
            'xml' => 'xml',
            'py' => 'python',
            'rb' => 'ruby',
            'go' => 'go',
            'rs' => 'rust',
            'java' => 'java',
            'c' => 'c',
            'cpp' => 'cpp',
            'sh' => 'bash',
            'yml' => 'yaml',
            'yaml' => 'yaml',
        ];

        return $languageMap[$extension] ?? 'text';
    }
}

<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Livewire;

use ArtisanBuild\AgentOsInstaller\Services\MarkdownRenderer;
use ArtisanBuild\AgentOsInstaller\Services\ProductConcatenationService;
use ArtisanBuild\AgentOsInstaller\Services\SpecConcatenationService;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Main viewer component for Agent OS documentation
 */
class AgentOsViewer extends Component
{
    public string $basePath;

    public string $path = '';

    public function mount(string $basePath, string $path = ''): void
    {
        $this->basePath = $basePath;
        $this->path = $path;
    }

    #[Computed]
    public function content(): string
    {
        // Default to Product view if no path specified
        if (empty($this->path)) {
            $defaultView = config('agent-os-installer.viewer.default_view', 'product');

            return $defaultView === 'product'
                ? $this->renderProduct()
                : $this->renderReadme();
        }

        // Handle specific paths
        if ($this->path === '.agent-os/product') {
            return $this->renderProduct();
        }

        if ($this->path === 'README.md') {
            return $this->renderReadme();
        }

        // Handle spec directories
        if (str_starts_with($this->path, '.agent-os/specs/')) {
            return $this->renderSpec();
        }

        // Handle individual markdown files
        return $this->renderFile();
    }

    public function render()
    {
        return view('agent-os-installer::livewire.agent-os-viewer');
    }

    protected function renderProduct(): string
    {
        $service = new ProductConcatenationService($this->basePath);
        $markdown = $service->concatenate();

        if (empty($markdown)) {
            return '<p class="text-gray-500 dark:text-gray-400">No product documentation found.</p>';
        }

        $renderer = new MarkdownRenderer;

        return $renderer->render($markdown);
    }

    protected function renderSpec(): string
    {
        // Extract spec folder name from path
        // Path format: .agent-os/specs/2025-11-02-spec-name
        $specFolder = str_replace('.agent-os/specs/', '', $this->path);

        $service = new SpecConcatenationService($this->basePath);
        $markdown = $service->concatenate($specFolder);

        if (empty($markdown)) {
            return '<p class="text-gray-500 dark:text-gray-400">No spec documentation found.</p>';
        }

        $renderer = new MarkdownRenderer;

        return $renderer->render($markdown);
    }

    protected function renderReadme(): string
    {
        $readmePath = $this->basePath.'/README.md';

        if (! File::exists($readmePath)) {
            return '<p class="text-gray-500 dark:text-gray-400">README.md not found.</p>';
        }

        $markdown = File::get($readmePath);
        $renderer = new MarkdownRenderer;

        return $renderer->render($markdown);
    }

    protected function renderFile(): string
    {
        $filePath = $this->basePath.'/'.$this->path;

        if (! File::exists($filePath)) {
            return '<p class="text-gray-500 dark:text-gray-400">File not found: '.$this->path.'</p>';
        }

        if (! str_ends_with($filePath, '.md')) {
            return '<p class="text-gray-500 dark:text-gray-400">Only markdown files (.md) are supported.</p>';
        }

        $markdown = File::get($filePath);
        $renderer = new MarkdownRenderer;

        return $renderer->render($markdown);
    }
}

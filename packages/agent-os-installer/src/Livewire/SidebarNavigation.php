<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Livewire;

use ArtisanBuild\AgentOsInstaller\Services\DirectoryScanner;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Sidebar navigation component for Agent OS documentation viewer
 */
class SidebarNavigation extends Component
{
    public string $basePath;

    public string $currentPath = '';

    public function mount(string $basePath, string $currentPath = ''): void
    {
        $this->basePath = $basePath;
        $this->currentPath = $currentPath;
    }

    #[Computed]
    public function navigationItems(): array
    {
        $scanner = new DirectoryScanner($this->basePath);
        $structure = $scanner->scan();

        $items = [];

        // Product folder
        if (isset($structure['product']) && ! empty($structure['product'])) {
            $items[] = [
                'label' => 'Product',
                'path' => '.agent-os/product',
                'type' => 'product',
                'active' => $this->isActive('.agent-os/product'),
            ];
        }

        // Specs
        if (! empty($structure['specs'])) {
            $items[] = [
                'label' => 'Specs',
                'type' => 'heading',
            ];

            foreach ($structure['specs'] as $spec) {
                // Convert absolute path to relative
                $relativePath = str_replace($this->basePath.'/', '', $spec['path']);

                $items[] = [
                    'label' => $spec['title'],
                    'path' => $relativePath,
                    'type' => 'spec',
                    'date' => $spec['date'],
                    'active' => $this->isActive($relativePath),
                ];
            }
        }

        // Additional directories
        foreach ($structure as $key => $value) {
            // Skip known keys
            if (in_array($key, ['product', 'specs', 'readme'])) {
                continue;
            }

            // Additional directory
            if (is_array($value) && isset($value['label'], $value['path'])) {
                $items[] = [
                    'label' => $value['label'],
                    'path' => $key,
                    'type' => 'additional',
                    'active' => $this->isActive($key),
                ];
            }
        }

        // README
        if (isset($structure['readme'])) {
            $items[] = [
                'label' => 'README',
                'path' => 'README.md',
                'type' => 'readme',
                'active' => $this->isActive('README.md'),
            ];
        }

        return $items;
    }

    public function render()
    {
        return view('agent-os-installer::livewire.sidebar-navigation');
    }

    protected function isActive(string $path): bool
    {
        return str_starts_with($this->currentPath, $path);
    }
}

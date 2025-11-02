<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Scans and parses Agent OS documentation directory structure
 */
class DirectoryScanner
{
    public function __construct(
        protected string $basePath
    ) {}

    /**
     * Scan the directory structure and return organized navigation data
     */
    public function scan(): array
    {
        $structure = [];

        // Get configured paths
        $paths = config('agent-os-installer.viewer.paths', ['.agent-os' => 'Agent OS Documentation']);

        foreach ($paths as $path => $label) {
            $fullPath = $this->basePath.'/'.$path;

            if (! File::exists($fullPath)) {
                continue;
            }

            // Handle .agent-os directory specially
            if ($path === '.agent-os') {
                $structure['product'] = $this->scanProductFolder($fullPath);
                $structure['specs'] = $this->scanSpecsFolder($fullPath);
            } else {
                // Handle additional directories
                $structure[$path] = $this->scanGenericFolder($fullPath, $label);
            }
        }

        // Add README.md if it exists
        $readmePath = $this->basePath.'/README.md';
        if (File::exists($readmePath)) {
            $structure['readme'] = [
                'label' => 'README',
                'path' => $readmePath,
            ];
        }

        return $structure;
    }

    /**
     * Scan the product folder
     */
    protected function scanProductFolder(string $basePath): array
    {
        $productPath = $basePath.'/product';

        if (! File::exists($productPath)) {
            return [];
        }

        $files = [];
        $expectedFiles = ['mission.md', 'roadmap.md', 'tech-stack.md', 'decisions.md'];

        foreach ($expectedFiles as $file) {
            $filePath = $productPath.'/'.$file;
            if (File::exists($filePath)) {
                $files[] = [
                    'name' => $file,
                    'path' => $filePath,
                    'label' => $this->formatFileName($file),
                ];
            }
        }

        return [
            'label' => 'Product',
            'files' => $files,
        ];
    }

    /**
     * Scan the specs folder and extract spec information
     */
    protected function scanSpecsFolder(string $basePath): array
    {
        $specsPath = $basePath.'/specs';

        if (! File::exists($specsPath)) {
            return [];
        }

        $specs = [];
        $directories = File::directories($specsPath);

        foreach ($directories as $dir) {
            $folderName = basename((string) $dir);

            // Parse folder name: YYYY-MM-DD-spec-name
            if (! preg_match('/^(\d{4}-\d{2}-\d{2})-(.+)$/', $folderName, $matches)) {
                continue;
            }

            $date = $matches[1];
            $name = $matches[2];

            $specs[] = [
                'date' => $date,
                'folder' => $folderName,
                'title' => $this->formatSpecTitle($name),
                'path' => $dir,
            ];
        }

        // Sort by date in reverse chronological order (newest first)
        usort($specs, fn ($a, $b) => strcmp($b['date'], $a['date']));

        return $specs;
    }

    /**
     * Scan a generic folder for markdown files
     */
    protected function scanGenericFolder(string $path, string $label): array
    {
        if (! File::exists($path)) {
            return [];
        }

        $files = File::allFiles($path);
        $markdownFiles = array_filter($files, fn ($file) => $file->getExtension() === 'md');

        return [
            'label' => $label,
            'path' => $path,
            'files' => array_map(fn ($file) => [
                'name' => $file->getFilename(),
                'path' => $file->getPathname(),
            ], $markdownFiles),
        ];
    }

    /**
     * Format a filename for display (remove .md, title case)
     */
    protected function formatFileName(string $filename): string
    {
        return Str::title(str_replace(['.md', '-'], ['', ' '], $filename));
    }

    /**
     * Format a spec title from kebab-case to Title Case
     */
    protected function formatSpecTitle(string $name): string
    {
        return Str::title(str_replace('-', ' ', $name));
    }
}

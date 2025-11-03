<?php

declare(strict_types=1);

namespace ArtisanBuild\JsonMarkdown;

use Illuminate\Contracts\Filesystem\Filesystem;
use InvalidArgumentException;

class MarkdownDirectory
{
    protected array $extensions;

    public function __construct(protected Filesystem $filesystem)
    {
        $this->extensions = config('json-markdown.extensions', ['.md', '.markdown']);
    }

    /**
     * Convert a directory of markdown files to JSON.
     */
    public function toJson(string $path): string
    {
        if (! $this->filesystem->exists($path)) {
            throw new InvalidArgumentException('Directory does not exist: ' . $path);
        }

        $structure = $this->buildDirectoryStructure($path);

        return json_encode($structure, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Build the directory structure recursively.
     */
    protected function buildDirectoryStructure(string $path): array
    {
        $structure = [
            'files' => [],
            'directories' => [],
        ];

        // Get all files in the current directory (not recursive)
        $allFiles = $this->filesystem->files($path);

        foreach ($allFiles as $file) {
            if ($this->isMarkdownFile($file)) {
                $structure['files'][] = [
                    'path' => basename($file),
                    'content' => $this->convertFileToJson($file),
                ];
            }
        }

        // Get all subdirectories
        $directories = $this->filesystem->directories($path);

        foreach ($directories as $directory) {
            $structure['directories'][] = [
                'path' => basename($directory),
                'contents' => $this->buildDirectoryStructure($directory),
            ];
        }

        return $structure;
    }

    /**
     * Check if a file is a markdown file based on configured extensions.
     */
    protected function isMarkdownFile(string $path): bool
    {
        foreach ($this->extensions as $extension) {
            if (str_ends_with(strtolower($path), $extension)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Convert a single markdown file to JSON structure.
     */
    protected function convertFileToJson(string $path): array
    {
        $markdown = $this->filesystem->get($path);
        $json = MarkdownToJson::convert($markdown);

        return json_decode($json, true);
    }

    /**
     * Create markdown files from JSON structure.
     */
    public function fromJson(string $json, string $basePath): void
    {
        $structure = json_decode($json, true);

        if (! is_array($structure)) {
            throw new InvalidArgumentException('Invalid JSON structure');
        }

        $this->writeDirectoryStructure($structure, $basePath);
    }

    /**
     * Write directory structure from JSON.
     */
    protected function writeDirectoryStructure(array $structure, string $basePath): void
    {
        // Ensure base directory exists
        if (! $this->filesystem->exists($basePath)) {
            $this->filesystem->makeDirectory($basePath, 0755, true);
        }

        // Write files
        foreach ($structure['files'] ?? [] as $file) {
            $filePath = $basePath . '/' . $file['path'];
            $markdown = JsonToMarkdown::convert(json_encode($file['content']));

            $overwrite = config('json-markdown.overwrite', true);

            if ($overwrite || ! $this->filesystem->exists($filePath)) {
                $this->filesystem->put($filePath, $markdown);
            }
        }

        // Write subdirectories
        foreach ($structure['directories'] ?? [] as $directory) {
            $dirPath = $basePath . '/' . $directory['path'];
            $this->writeDirectoryStructure($directory['contents'], $dirPath);
        }
    }
}

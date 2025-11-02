<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Services;

use Illuminate\Support\Facades\File;

/**
 * Searches Agent OS documentation files
 */
class AgentOsSearchService
{
    public function __construct(
        protected string $basePath
    ) {}

    /**
     * Search for a query across all markdown files
     */
    public function search(string $query, int $limit = 50): array
    {
        if (empty(trim($query))) {
            return [];
        }

        // Handle quoted phrases
        $isPhrase = str_starts_with($query, '"') && str_ends_with($query, '"');
        if ($isPhrase) {
            $query = trim($query, '"');
        }

        $results = [];
        $paths = config('agent-os-installer.viewer.paths', ['.agent-os' => 'Agent OS Documentation']);

        foreach ($paths as $path => $label) {
            $fullPath = $this->basePath.'/'.$path;

            if (! File::exists($fullPath)) {
                continue;
            }

            $files = File::allFiles($fullPath);

            foreach ($files as $file) {
                if ($file->getExtension() !== 'md') {
                    continue;
                }

                $content = File::get($file->getPathname());
                $matches = $this->searchInFile($content, $query, $isPhrase);

                if (count($matches) > 0) {
                    $results[] = [
                        'file' => $file->getPathname(),
                        'relative_path' => str_replace($this->basePath.'/', '', $file->getPathname()),
                        'source' => $label,
                        'matches' => count($matches),
                        'snippets' => array_slice($matches, 0, 3), // First 3 snippets
                    ];
                }
            }
        }

        // Add README.md if it exists
        $readmePath = $this->basePath.'/README.md';
        if (File::exists($readmePath)) {
            $content = File::get($readmePath);
            $matches = $this->searchInFile($content, $query, $isPhrase);

            if (count($matches) > 0) {
                $results[] = [
                    'file' => $readmePath,
                    'relative_path' => 'README.md',
                    'source' => 'README',
                    'matches' => count($matches),
                    'snippets' => array_slice($matches, 0, 3),
                ];
            }
        }

        // Sort by number of matches
        usort($results, fn ($a, $b) => $b['matches'] <=> $a['matches']);

        return array_slice($results, 0, $limit);
    }

    /**
     * Search within a file and return context snippets
     */
    protected function searchInFile(string $content, string $query, bool $isPhrase): array
    {
        $matches = [];
        $lines = explode("\n", $content);

        foreach ($lines as $lineNum => $line) {
            $found = $isPhrase
                ? stripos($line, $query) !== false
                : $this->containsWords($line, $query);

            if ($found) {
                $matches[] = [
                    'line' => $lineNum + 1,
                    'snippet' => $this->extractSnippet($line, $query, 150),
                ];
            }
        }

        return $matches;
    }

    /**
     * Check if line contains all words from query (case-insensitive)
     */
    protected function containsWords(string $line, string $query): bool
    {
        $words = explode(' ', $query);
        $line = strtolower($line);

        foreach ($words as $word) {
            if (stripos($line, strtolower($word)) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Extract a context snippet around the match
     */
    protected function extractSnippet(string $line, string $query, int $maxLength): string
    {
        if (strlen($line) <= $maxLength) {
            return $line;
        }

        $pos = stripos($line, $query);
        if ($pos === false) {
            return substr($line, 0, $maxLength).'...';
        }

        $start = max(0, $pos - ($maxLength / 2));
        $snippet = substr($line, $start, $maxLength);

        if ($start > 0) {
            $snippet = '...'.$snippet;
        }
        if (strlen($line) > $start + $maxLength) {
            $snippet .= '...';
        }

        return $snippet;
    }
}

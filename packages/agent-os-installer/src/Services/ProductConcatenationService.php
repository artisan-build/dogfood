<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Concatenates product folder markdown files into a single view
 */
class ProductConcatenationService
{
    protected array $fileOrder = [
        'mission.md',
        'roadmap.md',
        'tech-stack.md',
        'decisions.md',
    ];

    public function __construct(
        protected string $basePath
    ) {}

    /**
     * Concatenate all product files into a single markdown string
     */
    public function concatenate(): string
    {
        $productPath = $this->basePath.'/.agent-os/product';

        if (! File::exists($productPath)) {
            return '';
        }

        $sections = [];
        $isFirst = true;

        foreach ($this->fileOrder as $filename) {
            $filePath = $productPath.'/'.$filename;

            if (! File::exists($filePath)) {
                continue;
            }

            $content = File::get($filePath);

            if ($isFirst) {
                // Keep the first file's heading as-is
                $sections[] = $content;
                $isFirst = false;
            } else {
                // Strip top-level heading and add generated section header
                $content = $this->stripTopLevelHeading($content);
                $sectionHeader = $this->generateSectionHeader($filename);
                $sections[] = "---\n\n{$sectionHeader}\n\n{$content}";
            }
        }

        return implode("\n\n", $sections);
    }

    /**
     * Strip the top-level heading from content
     */
    protected function stripTopLevelHeading(string $content): string
    {
        // Remove first line if it starts with # (top-level heading)
        $lines = explode("\n", $content);

        if (isset($lines[0]) && str_starts_with(trim($lines[0]), '#')) {
            array_shift($lines);

            // Also remove any blank lines immediately after the heading
            while (isset($lines[0]) && trim($lines[0]) === '') {
                array_shift($lines);
            }
        }

        return implode("\n", $lines);
    }

    /**
     * Generate a section header from filename
     */
    protected function generateSectionHeader(string $filename): string
    {
        $name = str_replace('.md', '', $filename);
        $title = Str::title(str_replace('-', ' ', $name));

        return "# {$title}";
    }
}

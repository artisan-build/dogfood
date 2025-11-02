<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Concatenates spec-related markdown files into a single unified view
 */
class SpecConcatenationService
{
    public function __construct(
        protected string $basePath
    ) {}

    /**
     * Concatenate all files for a given spec into a single markdown string
     */
    public function concatenate(string $specFolder): string
    {
        $specPath = $this->basePath.'/.agent-os/specs/'.$specFolder;

        if (! File::exists($specPath)) {
            return '';
        }

        $sections = [];

        // 1. Start with spec.md
        $specFile = $specPath.'/spec.md';
        if (File::exists($specFile)) {
            $sections[] = File::get($specFile);
        }

        // 2. Add all sub-specs alphabetically
        $subSpecsPath = $specPath.'/sub-specs';
        if (File::exists($subSpecsPath)) {
            $subSpecs = File::files($subSpecsPath);

            // Sort alphabetically
            usort($subSpecs, fn ($a, $b) => strcmp($a->getFilename(), $b->getFilename()));

            foreach ($subSpecs as $file) {
                $content = File::get($file->getPathname());
                $content = $this->stripTopLevelHeading($content);
                $sectionHeader = $this->generateSectionHeader($file->getFilename());

                $sections[] = "---\n\n{$sectionHeader}\n\n{$content}";
            }
        }

        // 3. Add tasks.md at the end
        $tasksFile = $specPath.'/tasks.md';
        if (File::exists($tasksFile)) {
            $content = File::get($tasksFile);
            $content = $this->stripTopLevelHeading($content);

            $sections[] = "---\n\n# Tasks\n\n{$content}";
        }

        return implode("\n\n", $sections);
    }

    /**
     * Strip the top-level heading from content
     */
    protected function stripTopLevelHeading(string $content): string
    {
        $lines = explode("\n", $content);

        if (isset($lines[0]) && str_starts_with(trim($lines[0]), '#')) {
            array_shift($lines);

            // Remove blank lines immediately after the heading
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

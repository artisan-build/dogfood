<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Services;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;
use League\CommonMark\MarkdownConverter;

/**
 * Renders markdown content to HTML with Agent OS specific features
 */
class MarkdownRenderer
{
    protected MarkdownConverter $converter;

    public function __construct()
    {
        $config = [
            'html_input' => 'allow', // Allow HTML since all content is developer-created and trusted
            'allow_unsafe_links' => false,
        ];

        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addExtension(new TableExtension);
        $environment->addExtension(new TaskListExtension);

        $this->converter = new MarkdownConverter($environment);
    }

    /**
     * Render markdown to HTML
     */
    public function render(string $markdown): string
    {
        // Convert Agent OS @ reference links before rendering
        $markdown = $this->convertReferenceLinks($markdown);

        return $this->converter->convert($markdown)->getContent();
    }

    /**
     * Convert Agent OS @ reference links to internal navigation or anchor links
     *
     * For sub-specs within the same spec folder, converts to anchor links (#section)
     * For other references, converts to navigation links (/path)
     *
     * Example: @.agent-os/specs/2025-11-01-spec/sub-specs/technical-spec.md becomes #technical-spec
     * Example: @.agent-os/product/mission.md becomes /.agent-os/product/mission.md
     */
    protected function convertReferenceLinks(string $markdown): string
    {
        return preg_replace_callback(
            '/@(\.agent-os\/[^\s\)]+\.md)/',
            function ($matches) {
                $path = $matches[1];
                $displayPath = str_replace('.agent-os/', '', $path);

                // Check if this is a sub-spec reference (within specs/.../sub-specs/)
                if (preg_match('/\.agent-os\/specs\/[^\/]+\/sub-specs\/(.+)\.md$/', $path, $subMatches)) {
                    // Convert to anchor link using the filename
                    $filename = $subMatches[1];
                    $anchor = strtolower(str_replace('_', '-', $filename));

                    return "[{$displayPath}](#{$anchor})";
                }

                // Check if this is a tasks.md reference within a spec
                if (preg_match('/\.agent-os\/specs\/[^\/]+\/tasks\.md$/', $path)) {
                    return "[{$displayPath}](#tasks)";
                }

                // For all other references, use navigation links
                return "[{$displayPath}](/{$path})";
            },
            $markdown
        );
    }
}

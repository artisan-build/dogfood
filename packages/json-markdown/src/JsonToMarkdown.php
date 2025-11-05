<?php

declare(strict_types=1);

namespace ArtisanBuild\JsonMarkdown;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

class JsonToMarkdown
{
    /**
     * Convert JSON string to Markdown.
     */
    public static function convert(string $json): string
    {
        $data = json_decode($json, true);

        if (! is_array($data) || ! isset($data['type']) || $data['type'] !== 'document') {
            throw new InvalidArgumentException('Invalid JSON structure. Expected a document with type "document".');
        }

        $frontmatter = $data['frontmatter'] ?? null;
        $children = $data['children'] ?? [];

        $markdown = [];

        // Add frontmatter if present
        if ($frontmatter !== null && ! empty($frontmatter)) {
            $markdown[] = self::processFrontmatter($frontmatter);
        }

        // Process children
        foreach ($children as $child) {
            $result = self::processNode($child);
            if ($result !== null) {
                $markdown[] = $result;
            }
        }

        if (empty($markdown)) {
            return '';
        }

        return implode("\n\n", $markdown);
    }

    /**
     * Process frontmatter into YAML.
     */
    protected static function processFrontmatter(array $frontmatter): string
    {
        $yaml = Yaml::dump($frontmatter, 10, 2);

        return "---\n".rtrim($yaml)."\n---";
    }

    /**
     * Process individual nodes.
     */
    protected static function processNode(array $node): ?string
    {
        $type = $node['type'] ?? null;

        return match ($type) {
            'heading' => self::processHeading($node),
            'paragraph' => self::processParagraph($node),
            'table' => self::processTable($node),
            'list' => self::processList($node),
            'code' => self::processCode($node),
            default => null,
        };
    }

    /**
     * Process a heading node.
     */
    protected static function processHeading(array $node): string
    {
        $level = $node['level'] ?? 1;
        $content = $node['content'] ?? '';

        return str_repeat('#', $level).' '.$content;
    }

    /**
     * Process a paragraph node.
     */
    protected static function processParagraph(array $node): string
    {
        $content = $node['content'] ?? '';

        // If content is an array (inline formatting), process it
        if (is_array($content)) {
            return self::processInlineContent($content);
        }

        return $content;
    }

    /**
     * Process inline content array.
     */
    protected static function processInlineContent(array $content): string
    {
        $result = '';

        foreach ($content as $item) {
            $type = $item['type'] ?? 'text';
            $text = $item['content'] ?? '';

            $result .= match ($type) {
                'text' => $text,
                'strong' => '**'.$text.'**',
                'emphasis' => '*'.$text.'*',
                'strong-emphasis' => '***'.$text.'***',
                'strikethrough' => '~~'.$text.'~~',
                'link' => '['.$text.']('.($item['url'] ?? '').')',
                default => $text,
            };
        }

        return $result;
    }

    /**
     * Process a table node.
     */
    protected static function processTable(array $node): string
    {
        $header = $node['header'] ?? [];
        $rows = $node['rows'] ?? [];

        $lines = [];

        // Add header row
        if (! empty($header)) {
            $lines[] = '| '.implode(' | ', $header).' |';

            // Add separator row
            $separator = array_map(fn () => '----------', $header);
            $lines[] = '|'.implode('|', $separator).'|';
        }

        // Add data rows
        foreach ($rows as $row) {
            $lines[] = '| '.implode(' | ', $row).' |';
        }

        return implode("\n", $lines);
    }

    /**
     * Process a list node.
     */
    protected static function processList(array $node): string
    {
        $ordered = $node['ordered'] ?? false;
        $items = $node['items'] ?? [];

        $lines = [];
        $counter = 1;

        foreach ($items as $item) {
            // Check if this is a task list item
            if (is_array($item) && isset($item['checked'])) {
                $checkbox = $item['checked'] ? '[x]' : '[ ]';
                $content = $item['content'] ?? '';
                $lines[] = '- '.$checkbox.' '.$content;
            } else {
                // Regular list item
                $content = is_array($item) ? ($item['content'] ?? '') : $item;
                if ($ordered) {
                    $lines[] = $counter.'. '.$content;
                    $counter++;
                } else {
                    $lines[] = '- '.$content;
                }
            }
        }

        return implode("\n", $lines);
    }

    /**
     * Process a code block node.
     */
    protected static function processCode(array $node): string
    {
        $language = $node['language'] ?? null;
        $content = $node['content'] ?? '';

        // Fenced code block
        if ($language !== null) {
            return '```'.$language."\n".$content."\n```";
        }

        // Indented code block
        $lines = explode("\n", $content);
        $indented = array_map(fn ($line) => '    '.$line, $lines);

        return implode("\n", $indented);
    }
}

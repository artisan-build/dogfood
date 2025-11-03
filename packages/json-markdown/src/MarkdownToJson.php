<?php

declare(strict_types=1);

namespace ArtisanBuild\JsonMarkdown;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\Data\SymfonyYamlFrontMatterParser;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterParser;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\ListBlock;
use League\CommonMark\Extension\CommonMark\Node\Block\ListItem;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension\Strikethrough;
use League\CommonMark\Extension\Table\Table;
use League\CommonMark\Extension\Table\TableCell;
use League\CommonMark\Extension\Table\TableRow;
use League\CommonMark\Extension\Table\TableSection;
use League\CommonMark\Extension\TaskList\TaskListItemMarker;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Node\Inline\Link;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Node\Node;
use League\CommonMark\Parser\MarkdownParser;

class MarkdownToJson
{
    /**
     * Convert Markdown string to JSON.
     */
    public static function convert(string $markdown): string
    {
        // Parse frontmatter first
        $frontMatterParser = new FrontMatterParser(new SymfonyYamlFrontMatterParser);
        $frontMatterResult = $frontMatterParser->parse($markdown);

        $frontMatter = $frontMatterResult->getFrontMatter();
        $content = $frontMatterResult->getContent();

        // Parse the content (without frontmatter) as Markdown
        $environment = self::createEnvironment();
        $parser = new MarkdownParser($environment);
        $document = $parser->parse($content);

        $structure = self::convertNodeToArray($document);

        // Add frontmatter if present
        if ($frontMatter !== null && ! empty($frontMatter)) {
            $structure['frontmatter'] = $frontMatter;
        }

        $prettyPrint = config('json-markdown.pretty_print', true);

        return json_encode(
            $structure,
            $prettyPrint ? JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES : JSON_UNESCAPED_SLASHES
        );
    }

    /**
     * Create configured CommonMark environment with extensions.
     */
    protected static function createEnvironment(): Environment
    {
        $config = [
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
        ];

        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new FrontMatterExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addExtension(new AutolinkExtension);

        return $environment;
    }

    /**
     * Convert a CommonMark node to array structure.
     */
    protected static function convertNodeToArray(Node $node): array
    {
        $result = [];

        if ($node instanceof Document) {
            $result['type'] = 'document';
            $result['children'] = [];

            foreach ($node->children() as $child) {
                $childData = self::processNode($child);
                if ($childData !== null) {
                    $result['children'][] = $childData;
                }
            }

            return $result;
        }

        return $result;
    }

    /**
     * Process individual nodes.
     */
    protected static function processNode(Node $node): ?array
    {
        $className = get_class($node);

        // Handle Heading nodes
        if (str_contains($className, 'Heading')) {
            return [
                'type' => 'heading',
                'level' => $node->getLevel(),
                'content' => self::extractTextContent($node),
            ];
        }

        // Handle Paragraph nodes
        if ($node instanceof Paragraph) {
            $content = self::extractInlineContent($node);

            return [
                'type' => 'paragraph',
                'content' => is_array($content) ? $content : $content,
            ];
        }

        // Handle Table nodes
        if ($node instanceof Table) {
            return self::processTable($node);
        }

        // Handle List nodes
        if ($node instanceof ListBlock) {
            return self::processList($node);
        }

        // Handle FencedCode nodes
        if ($node instanceof FencedCode) {
            return [
                'type' => 'code',
                'language' => $node->getInfo() ?: null,
                'content' => rtrim($node->getLiteral()),
            ];
        }

        // Handle IndentedCode nodes
        if ($node instanceof IndentedCode) {
            return [
                'type' => 'code',
                'language' => null,
                'content' => rtrim($node->getLiteral()),
            ];
        }

        return null;
    }

    /**
     * Extract text content from a node.
     */
    protected static function extractTextContent(Node $node): string
    {
        $text = '';

        foreach ($node->children() as $child) {
            if ($child instanceof Text) {
                $text .= $child->getLiteral();
            } else {
                // Recursively extract from nested nodes
                $text .= self::extractTextContent($child);
            }
        }

        return $text;
    }

    /**
     * Extract inline content with formatting information.
     */
    protected static function extractInlineContent(Node $node): array|string
    {
        $hasFormatting = false;
        $content = [];

        foreach ($node->children() as $child) {
            $childClass = get_class($child);

            if ($child instanceof Text) {
                $content[] = [
                    'type' => 'text',
                    'content' => $child->getLiteral(),
                ];
            } elseif ($child instanceof Strikethrough || str_contains($childClass, 'Strikethrough')) {
                $hasFormatting = true;
                $content[] = [
                    'type' => 'strikethrough',
                    'content' => self::extractTextContent($child),
                ];
            } elseif ($child instanceof Link || str_contains($childClass, 'Link')) {
                $hasFormatting = true;
                $content[] = [
                    'type' => 'link',
                    'url' => $child->getUrl(),
                    'content' => self::extractTextContent($child),
                ];
            } else {
                // For other inline nodes, just extract text
                $literal = self::extractTextContent($child);
                if (! empty($literal)) {
                    $content[] = [
                        'type' => 'text',
                        'content' => $literal,
                    ];
                }
            }
        }

        // If no special formatting, return plain text
        if (! $hasFormatting) {
            return self::extractTextContent($node);
        }

        return $content;
    }

    /**
     * Process a table node.
     */
    protected static function processTable(Table $table): array
    {
        $header = [];
        $rows = [];

        foreach ($table->children() as $section) {
            if (! $section instanceof TableSection) {
                continue;
            }

            foreach ($section->children() as $row) {
                if (! $row instanceof TableRow) {
                    continue;
                }

                $rowData = [];
                foreach ($row->children() as $cell) {
                    if ($cell instanceof TableCell) {
                        $rowData[] = self::extractTextContent($cell);
                    }
                }

                if ($section->isHead()) {
                    $header = $rowData;
                } else {
                    $rows[] = $rowData;
                }
            }
        }

        return [
            'type' => 'table',
            'header' => $header,
            'rows' => $rows,
        ];
    }

    /**
     * Process a list node.
     */
    protected static function processList(ListBlock $list): array
    {
        $items = [];

        foreach ($list->children() as $item) {
            if (! $item instanceof ListItem) {
                continue;
            }

            $isTaskList = false;
            $checked = false;

            // Check for task list marker - need to check deeply
            $walker = $item->walker();
            while ($event = $walker->next()) {
                $node = $event->getNode();
                if ($node instanceof TaskListItemMarker && $event->isEntering()) {
                    $isTaskList = true;
                    $checked = $node->isChecked();
                    break;
                }
            }

            $content = self::extractTextContent($item);

            if ($isTaskList) {
                $items[] = [
                    'checked' => $checked,
                    'content' => trim($content),
                ];
            } else {
                $items[] = $content;
            }
        }

        $result = [
            'type' => 'list',
            'ordered' => $list->getListData()->type === ListBlock::TYPE_ORDERED,
            'items' => $items,
        ];

        return $result;
    }
}

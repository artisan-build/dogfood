<?php

declare(strict_types=1);

namespace ArtisanBuild\JsonMarkdown;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Node\Block\Paragraph;
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
        $environment = self::createEnvironment();
        $parser = new MarkdownParser($environment);

        $document = $parser->parse($markdown);

        $structure = self::convertNodeToArray($document);

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
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new FrontMatterExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);

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
            return [
                'type' => 'paragraph',
                'content' => self::extractTextContent($node),
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
}

<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Messages\Actions;

use ArtisanBuild\Bench\Attributes\ChatGPT;
use DOMDocument;

#[ChatGPT]
class ExtractMessagePreview
{
    public function __invoke(string $content, ?int $limit = null)
    {
        $limit ??= 500;

        $doc = new DOMDocument();
        $doc->loadXML('<div>' . mb_encode_numericentity(
            htmlspecialchars_decode(
                htmlentities($content, ENT_NOQUOTES, 'UTF-8', false),
                ENT_IGNORE,
            ),
            [0x80, 0x10FFFF, 0, ~0],
            'UTF-8',
        ) . '</div>');

        $visibleTextCount = 0;
        $previewContent = '';
        $openTags = [];
        $stop = false;

        foreach ($doc->getElementsByTagName('div')->item(0)->childNodes as $node) {
            $this->processNode($node, $limit, $previewContent, $visibleTextCount, $openTags, $stop);
            if ($stop) {
                break;
            }
        }

        // Close any unclosed tags
        while ($tag = array_pop($openTags)) {
            $previewContent .= "</{$tag}>";
        }

        return trim(html_entity_decode($previewContent));
    }

    protected function processNode($node, $limit, &$previewContent, &$visibleTextCount, &$openTags, &$stop): void
    {
        if ($stop) {
            return;
        }

        if (XML_TEXT_NODE === $node->nodeType) {
            $nodeText = $node->nodeValue;
            $remaining = $limit - $visibleTextCount;

            if ($visibleTextCount + mb_strlen($nodeText) > $limit) {
                $previewContent .= htmlspecialchars(mb_substr($nodeText, 0, $remaining));
                $stop = true;
            } else {
                $previewContent .= htmlspecialchars($nodeText);
                $visibleTextCount += mb_strlen($nodeText);
            }
        } elseif (XML_ELEMENT_NODE === $node->nodeType) {
            // Add opening tag
            $openTag = '<' . $node->nodeName;
            foreach ($node->attributes as $attr) {
                $openTag .= ' ' . $attr->nodeName . '="' . htmlspecialchars($attr->nodeValue) . '"';
            }
            $openTag .= '>';

            $previewContent .= $openTag;
            $openTags[] = $node->nodeName;

            foreach ($node->childNodes as $child) {
                $this->processNode($child, $limit, $previewContent, $visibleTextCount, $openTags, $stop);
                if ($stop) {
                    break;
                }
            }

            if ( ! $stop) {
                // If not stopped, close the current tag
                array_pop($openTags);
                $previewContent .= "</" . $node->nodeName . ">";
            }
        }
    }
}

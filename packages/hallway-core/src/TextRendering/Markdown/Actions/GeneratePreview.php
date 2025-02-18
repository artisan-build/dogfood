<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\TextRendering\Markdown\Actions;

use ArtisanBuild\Hallway\TextRendering\Markdown\MarkdownContent;
use Closure;
use DOMDocument;

class GeneratePreview
{
    public function __invoke(MarkdownContent $content, Closure $next)
    {
        // TODO: This is going to be a configuration variable, but we need it in the database
        $limit = 500;
        libxml_use_internal_errors(true);
        $doc = new DOMDocument;
        $doc->loadHTML('<div>'.mb_encode_numericentity(
            htmlspecialchars_decode(
                htmlentities($content->parsed, ENT_NOQUOTES, 'UTF-8', false),
                ENT_IGNORE,
            ),
            [0x80, 0x10FFFF, 0, ~0],
            'UTF-8',
        ).'</div>');

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

        $content->preview = trim(html_entity_decode((string) $previewContent));

        return $next($content);
    }

    protected function processNode($node, $limit, &$previewContent, &$visibleTextCount, &$openTags, &$stop): void
    {
        if ($stop) {
            return;
        }

        if ($node->nodeType === XML_TEXT_NODE) {
            $nodeText = $node->nodeValue;
            $remaining = $limit - $visibleTextCount;

            if ($visibleTextCount + mb_strlen((string) $nodeText) > $limit) {
                $previewContent .= htmlspecialchars(mb_substr((string) $nodeText, 0, $remaining));
                $stop = true;
            } else {
                $previewContent .= htmlspecialchars((string) $nodeText);
                $visibleTextCount += mb_strlen((string) $nodeText);
            }
        } elseif ($node->nodeType === XML_ELEMENT_NODE) {
            // Add opening tag
            $openTag = '<'.$node->nodeName;
            foreach ($node->attributes as $attr) {
                $openTag .= ' '.$attr->nodeName.'="'.htmlspecialchars((string) $attr->nodeValue).'"';
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

            if (! $stop) {
                // If not stopped, close the current tag
                array_pop($openTags);
                $previewContent .= '</'.$node->nodeName.'>';
            }
        }
    }
}

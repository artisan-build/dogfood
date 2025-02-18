<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\TextRendering\Markdown;

class CopyEmbeddableTagsToNewLines
{
    public function __invoke(string $content)
    {
        // Define a regex pattern to match URLs you want to embed
        $embedPattern = '/(?<!\n)\bhttps?:\/\/\S+\b/';
        $embeds = [];

        // Find all embeddable links and add to array
        preg_match_all($embedPattern, $content, $matches);
        if ( ! empty($matches[0])) {
            $embeds = array_unique($matches[0]);
        }

        // Append each link on a new line at the end of the content
        if ( ! empty($embeds)) {
            $content .= "\n\n" . implode("\n", $embeds) . "\n";
        }

        return $content;
    }
}

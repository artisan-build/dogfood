<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\TextRendering\Markdown;

use ArtisanBuild\Hallway\TextRendering\Markdown\Actions\ConvertHeadingsToFlux;
use ArtisanBuild\Hallway\TextRendering\Markdown\Actions\ConvertParagraphsToFluxText;
use ArtisanBuild\Hallway\TextRendering\Markdown\Actions\ConvertUnembeddedLinksToFlux;
use ArtisanBuild\Hallway\TextRendering\Markdown\Actions\ExtractEmbeddableLinks;
use ArtisanBuild\Hallway\TextRendering\Markdown\Actions\GeneratePreview;
use ArtisanBuild\Hallway\TextRendering\Markdown\Actions\ParseMarkdownToHtml;
use Illuminate\Support\Facades\Pipeline;

class ConvertMarkdownToFluxUI
{
    public function __invoke(string $contents)
    {
        return Pipeline::send(MarkdownContent::make($contents))
            ->through([
                ExtractEmbeddableLinks::class,
                ParseMarkdownToHtml::class,
                GeneratePreview::class,
                ConvertParagraphsToFluxText::class,
                ConvertHeadingsToFlux::class,
                ConvertUnembeddedLinksToFlux::class,
            ])
            ->thenReturn();
    }
}

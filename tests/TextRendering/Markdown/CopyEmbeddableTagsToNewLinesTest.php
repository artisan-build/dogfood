<?php

declare(strict_types=1);

use ArtisanBuild\Hallway\TextRendering\Markdown\CopyEmbeddableTagsToNewLines;

describe('copy embeddable links to new lines', function (): void {
    it('just returns the original content if no embeddable links exist', function (): void {
        $original = file_get_contents(__DIR__ . '/__data/no-links.md');
        expect(app(CopyEmbeddableTagsToNewLines::class)($original))->toBe($original);
    });

    it('copies a single youtube link to a new line', function (): void {
        $original = file_get_contents(__DIR__ . '/__data/single-youtube-link.md');
        $expected = file_get_contents(__DIR__ . '/__data/single-youtube-link-expected.md');
        expect(app(CopyEmbeddableTagsToNewLines::class)($original))->toBe($expected);
    });

    it('copies a duplicated youtube link to a new line only once', function (): void {
        $original = file_get_contents(__DIR__ . '/__data/duplicate-youtube-link.md');
        $expected = file_get_contents(__DIR__ . '/__data/duplicate-youtube-link-expected.md');
        expect(app(CopyEmbeddableTagsToNewLines::class)($original))->toBe($expected);
    });

    it('copies all unique links', function (): void {
        $original = file_get_contents(__DIR__ . '/__data/multiple-unique-links.md');
        $expected = file_get_contents(__DIR__ . '/__data/multiple-unique-links-expected.md');
        expect(app(CopyEmbeddableTagsToNewLines::class)($original))->toBe($expected);
    });
});

<?php

declare(strict_types=1);

use ArtisanBuild\EmbeddableLinks\Services\MetadataFetcher;
use Illuminate\Support\Facades\Http;

test('extracts complete OpenGraph metadata', function (): void {
    $html = file_get_contents(__DIR__.'/../Fixtures/html/opengraph-complete.html');

    Http::fake([
        'example.com/*' => Http::response($html, 200),
    ]);

    $fetcher = new MetadataFetcher;
    $metadata = $fetcher->fetch('https://example.com/article');

    expect($metadata)
        ->toHaveKey('title')
        ->toHaveKey('description')
        ->toHaveKey('image')
        ->toHaveKey('url')
        ->and($metadata['title'])->toBe('Amazing Article Title')
        ->and($metadata['description'])->toBe('This is a comprehensive description of an amazing article with OpenGraph tags.')
        ->and($metadata['image'])->toBe('https://example.com/image.jpg');
});

test('handles partial OpenGraph metadata', function (): void {
    $html = file_get_contents(__DIR__.'/../Fixtures/html/opengraph-partial.html');

    Http::fake([
        'example.com/*' => Http::response($html, 200),
    ]);

    $fetcher = new MetadataFetcher;
    $metadata = $fetcher->fetch('https://example.com/partial');

    expect($metadata)
        ->toHaveKey('title')
        ->toHaveKey('url')
        ->and($metadata['title'])->toBe('Partial Article');
});

test('falls back to HTML title', function (): void {
    $html = file_get_contents(__DIR__.'/../Fixtures/html/generic-page.html');

    Http::fake([
        'example.com/*' => Http::response($html, 200),
    ]);

    $fetcher = new MetadataFetcher;
    $metadata = $fetcher->fetch('https://example.com/generic');

    expect($metadata['title'])->toBe('Generic Page Title');
});

test('extracts description from meta tag', function (): void {
    $html = file_get_contents(__DIR__.'/../Fixtures/html/generic-page.html');

    Http::fake([
        'example.com/*' => Http::response($html, 200),
    ]);

    $fetcher = new MetadataFetcher;
    $metadata = $fetcher->fetch('https://example.com/generic');

    expect($metadata['description'])->toBe('A generic page with standard meta tags.');
});

test('handles pages with no metadata', function (): void {
    $html = file_get_contents(__DIR__.'/../Fixtures/html/no-metadata.html');

    Http::fake([
        'example.com/*' => Http::response($html, 200),
    ]);

    $fetcher = new MetadataFetcher;
    $metadata = $fetcher->fetch('https://example.com/no-meta');

    expect($metadata)
        ->toHaveKey('title')
        ->toHaveKey('url');
});

test('handles HTTP errors gracefully', function (): void {
    Http::fake([
        'example.com/*' => Http::response('', 404),
    ]);

    $fetcher = new MetadataFetcher;
    $metadata = $fetcher->fetch('https://example.com/not-found');

    expect($metadata)
        ->toHaveKey('title')
        ->toHaveKey('url')
        ->and($metadata['title'])->toBe('Untitled Page');
});

test('handles timeouts gracefully', function (): void {
    Http::fake(fn () => throw new \Exception('Timeout'));

    $fetcher = new MetadataFetcher;
    $metadata = $fetcher->fetch('https://example.com/timeout');

    expect($metadata)->toHaveKey('title')
        ->and($metadata['title'])->toBe('Untitled Page');
});

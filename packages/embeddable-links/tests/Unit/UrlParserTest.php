<?php

declare(strict_types=1);

use ArtisanBuild\EmbeddableLinks\Services\UrlParser;

test('detects YouTube standard URL', function (): void {
    $parser = new UrlParser;

    expect($parser->parse('https://www.youtube.com/watch?v=dQw4w9WgXcQ'))
        ->service->toBe('youtube')
        ->id->toBe('dQw4w9WgXcQ');
});

test('detects YouTube shortened URL', function (): void {
    $parser = new UrlParser;

    expect($parser->parse('https://youtu.be/dQw4w9WgXcQ'))
        ->service->toBe('youtube')
        ->id->toBe('dQw4w9WgXcQ');
});

test('detects YouTube embed URL', function (): void {
    $parser = new UrlParser;

    expect($parser->parse('https://www.youtube.com/embed/dQw4w9WgXcQ'))
        ->service->toBe('youtube')
        ->id->toBe('dQw4w9WgXcQ');
});

test('detects Vimeo standard URL', function (): void {
    $parser = new UrlParser;

    expect($parser->parse('https://vimeo.com/123456789'))
        ->service->toBe('vimeo')
        ->id->toBe('123456789');
});

test('detects Vimeo player URL', function (): void {
    $parser = new UrlParser;

    expect($parser->parse('https://player.vimeo.com/video/123456789'))
        ->service->toBe('vimeo')
        ->id->toBe('123456789');
});

test('detects GitHub Gist URL', function (): void {
    $parser = new UrlParser;

    expect($parser->parse('https://gist.github.com/username/abc123def456'))
        ->service->toBe('github-gist')
        ->id->toBe('abc123def456');
});

test('detects GitHub Gist URL with file fragment', function (): void {
    $parser = new UrlParser;

    expect($parser->parse('https://gist.github.com/username/abc123def456#file-example-php'))
        ->service->toBe('github-gist')
        ->id->toBe('abc123def456');
});

test('detects generic URL with OpenGraph', function (): void {
    $parser = new UrlParser;

    expect($parser->parse('https://example.com/article'))
        ->service->toBe('generic')
        ->id->toBeNull();
});

test('handles HTTP protocol', function (): void {
    $parser = new UrlParser;

    expect($parser->parse('http://www.youtube.com/watch?v=dQw4w9WgXcQ'))
        ->service->toBe('youtube')
        ->id->toBe('dQw4w9WgXcQ');
});

test('rejects malformed URLs', function (): void {
    $parser = new UrlParser;

    expect(fn () => $parser->parse('not-a-valid-url'))
        ->toThrow(InvalidArgumentException::class);
});

test('validates URL format', function (): void {
    $parser = new UrlParser;

    expect($parser->isValid('https://example.com'))->toBeTrue()
        ->and($parser->isValid('not-a-url'))->toBeFalse()
        ->and($parser->isValid('ftp://example.com'))->toBeFalse();
});

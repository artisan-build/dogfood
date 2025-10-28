<?php

declare(strict_types=1);

use ArtisanBuild\EmbeddableLinks\Services\CacheManager;
use Illuminate\Support\Facades\Cache;

beforeEach(function (): void {
    Cache::flush();
});

test('caches metadata with default TTL', function (): void {
    $manager = new CacheManager;
    $metadata = ['title' => 'Test Page', 'description' => 'Test Description'];

    $manager->put('https://example.com', $metadata);

    expect($manager->get('https://example.com'))->toBe($metadata);
});

test('retrieves cached metadata', function (): void {
    $manager = new CacheManager;
    $metadata = ['title' => 'Cached Page'];

    $manager->put('https://example.com/cached', $metadata);

    expect($manager->get('https://example.com/cached'))->toBe($metadata)
        ->and($manager->has('https://example.com/cached'))->toBeTrue();
});

test('returns null for non-existent cache', function (): void {
    $manager = new CacheManager;

    expect($manager->get('https://example.com/not-cached'))->toBeNull()
        ->and($manager->has('https://example.com/not-cached'))->toBeFalse();
});

test('generates consistent cache keys', function (): void {
    $manager = new CacheManager;
    $metadata = ['title' => 'Consistent'];

    $manager->put('https://example.com/page', $metadata);

    expect($manager->get('https://example.com/page'))->toBe($metadata);
});

test('respects cache bypass', function (): void {
    $manager = new CacheManager;
    $metadata = ['title' => 'Bypass Test'];

    $manager->put('https://example.com/bypass', $metadata);

    expect($manager->shouldCache(false))->toBeFalse();
});

test('can forget cached metadata', function (): void {
    $manager = new CacheManager;
    $metadata = ['title' => 'Forget Me'];

    $manager->put('https://example.com/forget', $metadata);
    $manager->forget('https://example.com/forget');

    expect($manager->has('https://example.com/forget'))->toBeFalse();
});

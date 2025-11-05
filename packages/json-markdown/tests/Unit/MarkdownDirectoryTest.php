<?php

declare(strict_types=1);

use ArtisanBuild\JsonMarkdown\MarkdownDirectory;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('local');
});

test('it converts a single markdown file in directory to JSON', function (): void {
    // Create a test markdown file
    Storage::put('test/simple.md', '# Hello World');

    $directory = new MarkdownDirectory(Storage::disk('local'));
    $json = $directory->toJson('test');
    $result = json_decode($json, true);

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('files')
        ->and($result['files'])->toHaveCount(1)
        ->and($result['files'][0])->toMatchArray([
            'path' => 'simple.md',
            'content' => [
                'type' => 'document',
                'children' => [
                    ['type' => 'heading', 'level' => 1, 'content' => 'Hello World'],
                ],
            ],
        ]);
});

test('it converts multiple markdown files in flat directory to JSON', function (): void {
    Storage::put('docs/intro.md', '# Introduction');
    Storage::put('docs/guide.md', '# Guide');
    Storage::put('docs/reference.md', '# Reference');

    $directory = new MarkdownDirectory(Storage::disk('local'));
    $json = $directory->toJson('docs');
    $result = json_decode($json, true);

    expect($result['files'])->toHaveCount(3)
        ->and(collect($result['files'])->pluck('path')->sort()->values()->all())
        ->toBe(['guide.md', 'intro.md', 'reference.md']);
});

test('it converts nested directory structure to JSON', function (): void {
    Storage::put('docs/index.md', '# Docs Home');
    Storage::put('docs/guides/getting-started.md', '# Getting Started');
    Storage::put('docs/guides/advanced.md', '# Advanced');
    Storage::put('docs/api/endpoints.md', '# API Endpoints');

    $directory = new MarkdownDirectory(Storage::disk('local'));
    $json = $directory->toJson('docs');
    $result = json_decode($json, true);

    expect($result)->toHaveKey('files')
        ->and($result)->toHaveKey('directories')
        ->and($result['files'])->toHaveCount(1)
        ->and($result['directories'])->toHaveCount(2);
});

test('it only processes markdown files with configured extensions', function (): void {
    Storage::put('docs/readme.md', '# Readme');
    Storage::put('docs/notes.txt', 'Not markdown');
    Storage::put('docs/guide.markdown', '# Guide');
    Storage::put('docs/data.json', '{}');

    $directory = new MarkdownDirectory(Storage::disk('local'));
    $json = $directory->toJson('docs');
    $result = json_decode($json, true);

    expect($result['files'])->toHaveCount(2)
        ->and(collect($result['files'])->pluck('path')->sort()->values()->all())
        ->toBe(['guide.markdown', 'readme.md']);
});

test('it handles empty directories', function (): void {
    Storage::makeDirectory('empty');

    $directory = new MarkdownDirectory(Storage::disk('local'));
    $json = $directory->toJson('empty');
    $result = json_decode($json, true);

    expect($result['files'])->toBeArray()->toBeEmpty()
        ->and($result['directories'])->toBeArray()->toBeEmpty();
});

test('it throws exception for non-existent directory', function (): void {
    $directory = new MarkdownDirectory(Storage::disk('local'));
    $directory->toJson('nonexistent');
})->throws(InvalidArgumentException::class, 'Directory does not exist');

test('it creates files from JSON structure', function (): void {
    $json = json_encode([
        'files' => [
            [
                'path' => 'readme.md',
                'content' => [
                    'type' => 'document',
                    'children' => [
                        ['type' => 'heading', 'level' => 1, 'content' => 'README'],
                        ['type' => 'paragraph', 'content' => 'This is a test.'],
                    ],
                ],
            ],
        ],
        'directories' => [],
    ]);

    $directory = new MarkdownDirectory(Storage::disk('local'));
    $directory->fromJson($json, 'output');

    expect(Storage::exists('output/readme.md'))->toBeTrue()
        ->and(Storage::get('output/readme.md'))->toBe("# README\n\nThis is a test.");
});

test('it creates nested directory structures from JSON', function (): void {
    $json = json_encode([
        'files' => [
            ['path' => 'index.md', 'content' => ['type' => 'document', 'children' => [['type' => 'heading', 'level' => 1, 'content' => 'Index']]]],
        ],
        'directories' => [
            [
                'path' => 'guides',
                'contents' => [
                    'files' => [
                        ['path' => 'intro.md', 'content' => ['type' => 'document', 'children' => [['type' => 'heading', 'level' => 1, 'content' => 'Intro']]]],
                    ],
                    'directories' => [],
                ],
            ],
        ],
    ]);

    $directory = new MarkdownDirectory(Storage::disk('local'));
    $directory->fromJson($json, 'docs');

    expect(Storage::exists('docs/index.md'))->toBeTrue()
        ->and(Storage::exists('docs/guides/intro.md'))->toBeTrue()
        ->and(Storage::get('docs/index.md'))->toBe('# Index')
        ->and(Storage::get('docs/guides/intro.md'))->toBe('# Intro');
});

test('it respects overwrite configuration when creating files', function (): void {
    // Create an existing file
    Storage::put('test/existing.md', '# Original Content');

    $json = json_encode([
        'files' => [
            ['path' => 'existing.md', 'content' => ['type' => 'document', 'children' => [['type' => 'heading', 'level' => 1, 'content' => 'New Content']]]],
        ],
        'directories' => [],
    ]);

    // Test with overwrite enabled (default)
    config(['json-markdown.overwrite' => true]);
    $directory = new MarkdownDirectory(Storage::disk('local'));
    $directory->fromJson($json, 'test');

    expect(Storage::get('test/existing.md'))->toBe('# New Content');

    // Reset and test with overwrite disabled
    Storage::put('test/existing.md', '# Original Content');
    config(['json-markdown.overwrite' => false]);
    $directory->fromJson($json, 'test');

    expect(Storage::get('test/existing.md'))->toBe('# Original Content');
});

test('it throws exception for invalid JSON in fromJson', function (): void {
    $directory = new MarkdownDirectory(Storage::disk('local'));
    $directory->fromJson('invalid json', 'output');
})->throws(InvalidArgumentException::class, 'Invalid JSON structure');

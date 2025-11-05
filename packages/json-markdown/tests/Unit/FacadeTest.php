<?php

declare(strict_types=1);

use ArtisanBuild\JsonMarkdown\Facades\JsonToMarkdown;
use ArtisanBuild\JsonMarkdown\Facades\MarkdownDirectory;
use ArtisanBuild\JsonMarkdown\Facades\MarkdownToJson;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('local');
});

test('MarkdownToJson facade works', function (): void {
    $markdown = '# Hello World';
    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result)->toBeArray()
        ->and($result['type'])->toBe('document')
        ->and($result['children'][0]['type'])->toBe('heading')
        ->and($result['children'][0]['level'])->toBe(1)
        ->and($result['children'][0]['content'])->toBe('Hello World');
});

test('JsonToMarkdown facade works', function (): void {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            ['type' => 'heading', 'level' => 1, 'content' => 'Test Heading'],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    expect($markdown)->toBe('# Test Heading');
});

test('MarkdownDirectory facade works for toJson', function (): void {
    Storage::put('docs/readme.md', '# README');
    Storage::put('docs/guide.md', '# Guide');

    $json = MarkdownDirectory::toJson('docs');
    $result = json_decode($json, true);

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('files')
        ->and($result)->toHaveKey('directories')
        ->and($result['files'])->toHaveCount(2);
});

test('MarkdownDirectory facade works for fromJson', function (): void {
    $json = json_encode([
        'files' => [
            [
                'path' => 'test.md',
                'content' => [
                    'type' => 'document',
                    'children' => [
                        ['type' => 'heading', 'level' => 1, 'content' => 'Test'],
                    ],
                ],
            ],
        ],
        'directories' => [],
    ]);

    MarkdownDirectory::fromJson($json, 'output');

    expect(Storage::exists('output/test.md'))->toBeTrue()
        ->and(Storage::get('output/test.md'))->toBe('# Test');
});

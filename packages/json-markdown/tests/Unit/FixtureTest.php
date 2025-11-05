<?php

declare(strict_types=1);

use ArtisanBuild\JsonMarkdown\JsonToMarkdown;
use ArtisanBuild\JsonMarkdown\MarkdownToJson;

function loadFixture(string $name): string
{
    return file_get_contents(__DIR__.'/../fixtures/markdown/'.$name);
}

test('simple.md fixture converts correctly', function (): void {
    $markdown = loadFixture('simple.md');
    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result)->toBeArray()
        ->and($result['type'])->toBe('document')
        ->and($result['children'])->toHaveCount(2); // Heading + paragraph
});

test('complex.md fixture converts correctly', function (): void {
    $markdown = loadFixture('complex.md');
    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result)->toBeArray()
        ->and($result['type'])->toBe('document')
        ->and($result)->toHaveKey('frontmatter')
        ->and($result['frontmatter']['title'])->toBe('Complex Document');
});

test('minimal.md fixture converts correctly', function (): void {
    $markdown = loadFixture('minimal.md');
    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result)->toBeArray()
        ->and($result['children'])->toHaveCount(1)
        ->and($result['children'][0]['type'])->toBe('heading')
        ->and($result['children'][0]['content'])->toBe('Minimal');
});

test('empty.md fixture converts correctly', function (): void {
    $markdown = loadFixture('empty.md');
    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result)->toBeArray()
        ->and($result['children'])->toBeArray()
        ->and($result['children'])->toBeEmpty();
});

test('headings.md fixture converts correctly', function (): void {
    $markdown = loadFixture('headings.md');
    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'])->toHaveCount(6)
        ->and($result['children'][0]['level'])->toBe(1)
        ->and($result['children'][5]['level'])->toBe(6);
});

test('code-blocks.md fixture converts correctly', function (): void {
    $markdown = loadFixture('code-blocks.md');
    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    $codeBlocks = collect($result['children'])->where('type', 'code');
    expect($codeBlocks)->toHaveCount(3);
});

test('lists.md fixture converts correctly', function (): void {
    $markdown = loadFixture('lists.md');
    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    $lists = collect($result['children'])->where('type', 'list');
    expect($lists->count())->toBeGreaterThan(0);
});

test('with-frontmatter.md fixture converts correctly', function (): void {
    $markdown = loadFixture('with-frontmatter.md');
    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result)->toHaveKey('frontmatter')
        ->and($result['frontmatter']['title'])->toBe('Document with Frontmatter')
        ->and($result['frontmatter']['published'])->toBe(true);
});

test('no-frontmatter.md fixture converts correctly', function (): void {
    $markdown = loadFixture('no-frontmatter.md');
    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result)->not->toHaveKey('frontmatter');
});

test('tables.md fixture converts correctly', function (): void {
    $markdown = loadFixture('tables.md');
    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    $tables = collect($result['children'])->where('type', 'table');
    expect($tables)->toHaveCount(2);
});

test('task-lists.md fixture converts correctly', function (): void {
    $markdown = loadFixture('task-lists.md');
    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    $lists = collect($result['children'])->where('type', 'list');
    expect($lists->count())->toBeGreaterThan(0);
});

test('gfm-features.md fixture converts correctly', function (): void {
    $markdown = loadFixture('gfm-features.md');
    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result)->toBeArray()
        ->and($result['type'])->toBe('document')
        ->and($result['children'])->not->toBeEmpty();
});

test('all fixtures round-trip successfully', function (): void {
    $fixtures = [
        'simple.md',
        'complex.md',
        'minimal.md',
        'headings.md',
        'code-blocks.md',
        'lists.md',
        'with-frontmatter.md',
        'no-frontmatter.md',
        'tables.md',
        'task-lists.md',
        'gfm-features.md',
    ];

    foreach ($fixtures as $fixture) {
        $original = loadFixture($fixture);
        $json = MarkdownToJson::convert($original);
        $result = JsonToMarkdown::convert($json);

        // Verify the round-trip produces valid output
        expect($result)->toBeString()->not->toBeEmpty();
    }
});

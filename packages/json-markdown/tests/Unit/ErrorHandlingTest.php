<?php

declare(strict_types=1);

use ArtisanBuild\JsonMarkdown\JsonToMarkdown;
use ArtisanBuild\JsonMarkdown\MarkdownDirectory;
use ArtisanBuild\JsonMarkdown\MarkdownToJson;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('local');
});

// JsonToMarkdown error handling
test('JsonToMarkdown throws exception for invalid JSON', function (): void {
    JsonToMarkdown::convert('not valid json');
})->throws(InvalidArgumentException::class, 'Invalid JSON structure');

test('JsonToMarkdown throws exception for non-document type', function (): void {
    $json = json_encode(['type' => 'paragraph', 'content' => 'test']);
    JsonToMarkdown::convert($json);
})->throws(InvalidArgumentException::class, 'Invalid JSON structure');

test('JsonToMarkdown throws exception for missing type field', function (): void {
    $json = json_encode(['children' => []]);
    JsonToMarkdown::convert($json);
})->throws(InvalidArgumentException::class, 'Invalid JSON structure');

test('JsonToMarkdown throws exception for non-array JSON', function (): void {
    $json = json_encode('just a string');
    JsonToMarkdown::convert($json);
})->throws(InvalidArgumentException::class, 'Invalid JSON structure');

// MarkdownDirectory error handling
test('MarkdownDirectory throws exception for non-existent directory in toJson', function (): void {
    $directory = new MarkdownDirectory(Storage::disk('local'));
    $directory->toJson('does-not-exist');
})->throws(InvalidArgumentException::class, 'Directory does not exist');

test('MarkdownDirectory throws exception for invalid JSON in fromJson', function (): void {
    $directory = new MarkdownDirectory(Storage::disk('local'));
    $directory->fromJson('invalid json', 'output');
})->throws(InvalidArgumentException::class, 'Invalid JSON structure');

test('MarkdownDirectory throws exception for non-array JSON in fromJson', function (): void {
    $directory = new MarkdownDirectory(Storage::disk('local'));
    $directory->fromJson('"string"', 'output');
})->throws(InvalidArgumentException::class, 'Invalid JSON structure');

// Edge cases for MarkdownToJson
test('MarkdownToJson handles empty string', function (): void {
    $json = MarkdownToJson::convert('');
    $result = json_decode($json, true);

    expect($result)->toBeArray()
        ->and($result['type'])->toBe('document')
        ->and($result['children'])->toBeArray()
        ->and($result['children'])->toBeEmpty();
});

test('MarkdownToJson handles whitespace-only content', function (): void {
    $json = MarkdownToJson::convert("   \n\n   ");
    $result = json_decode($json, true);

    expect($result)->toBeArray()
        ->and($result['type'])->toBe('document');
});

test('MarkdownToJson handles special characters in content', function (): void {
    $markdown = '# Title with $pecial & <characters>';
    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'][0]['content'])->toContain('$pecial');
});

test('MarkdownToJson handles very long headings', function (): void {
    $longHeading = '# '.str_repeat('A', 500);
    $json = MarkdownToJson::convert($longHeading);
    $result = json_decode($json, true);

    expect($result['children'][0]['type'])->toBe('heading')
        ->and(strlen((string) $result['children'][0]['content']))->toBe(500);
});

// Edge cases for JsonToMarkdown
test('JsonToMarkdown handles empty children array', function (): void {
    $json = json_encode(['type' => 'document', 'children' => []]);
    $markdown = JsonToMarkdown::convert($json);

    expect($markdown)->toBe('');
});

test('JsonToMarkdown handles missing children key', function (): void {
    $json = json_encode(['type' => 'document']);
    $markdown = JsonToMarkdown::convert($json);

    expect($markdown)->toBe('');
});

test('JsonToMarkdown handles unknown node types gracefully', function (): void {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            ['type' => 'unknown-type', 'content' => 'test'],
            ['type' => 'heading', 'level' => 1, 'content' => 'Valid Heading'],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    // Should skip unknown type and process valid nodes
    expect($markdown)->toBe('# Valid Heading');
});

// Edge cases for MarkdownDirectory
test('MarkdownDirectory handles empty directory', function (): void {
    Storage::makeDirectory('empty');

    $directory = new MarkdownDirectory(Storage::disk('local'));
    $json = $directory->toJson('empty');
    $result = json_decode($json, true);

    expect($result['files'])->toBeArray()->toBeEmpty()
        ->and($result['directories'])->toBeArray()->toBeEmpty();
});

test('MarkdownDirectory handles directory with only non-markdown files', function (): void {
    Storage::put('mixed/file.txt', 'text');
    Storage::put('mixed/file.json', '{}');
    Storage::put('mixed/file.php', '<?php');

    $directory = new MarkdownDirectory(Storage::disk('local'));
    $json = $directory->toJson('mixed');
    $result = json_decode($json, true);

    expect($result['files'])->toBeEmpty();
});

test('MarkdownDirectory handles deeply nested structures', function (): void {
    Storage::put('level1/level2/level3/level4/deep.md', '# Deep');

    $directory = new MarkdownDirectory(Storage::disk('local'));
    $json = $directory->toJson('level1');
    $result = json_decode($json, true);

    expect($result['directories'])->toHaveCount(1)
        ->and($result['directories'][0]['path'])->toBe('level2');
});

test('MarkdownDirectory handles special characters in filenames', function (): void {
    Storage::put('special/file-with-dashes.md', '# Test');
    Storage::put('special/file_with_underscores.md', '# Test');
    Storage::put('special/file.with.dots.md', '# Test');

    $directory = new MarkdownDirectory(Storage::disk('local'));
    $json = $directory->toJson('special');
    $result = json_decode($json, true);

    expect($result['files'])->toHaveCount(3);
});

test('MarkdownDirectory respects overwrite=false configuration', function (): void {
    Storage::put('test/existing.md', '# Original');

    $json = json_encode([
        'files' => [
            ['path' => 'existing.md', 'content' => ['type' => 'document', 'children' => [['type' => 'heading', 'level' => 1, 'content' => 'New']]]],
        ],
        'directories' => [],
    ]);

    config(['json-markdown.overwrite' => false]);
    $directory = new MarkdownDirectory(Storage::disk('local'));
    $directory->fromJson($json, 'test');

    expect(Storage::get('test/existing.md'))->toBe('# Original');
});

test('MarkdownDirectory respects overwrite=true configuration', function (): void {
    Storage::put('test/existing.md', '# Original');

    $json = json_encode([
        'files' => [
            ['path' => 'existing.md', 'content' => ['type' => 'document', 'children' => [['type' => 'heading', 'level' => 1, 'content' => 'New']]]],
        ],
        'directories' => [],
    ]);

    config(['json-markdown.overwrite' => true]);
    $directory = new MarkdownDirectory(Storage::disk('local'));
    $directory->fromJson($json, 'test');

    expect(Storage::get('test/existing.md'))->toBe('# New');
});

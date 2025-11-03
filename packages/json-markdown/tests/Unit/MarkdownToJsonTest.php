<?php

declare(strict_types=1);

use ArtisanBuild\JsonMarkdown\MarkdownToJson;

test('it converts a simple paragraph to JSON structure', function () {
    $markdown = 'This is a simple paragraph.';

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result)->toBeArray()
        ->and($result['type'])->toBe('document')
        ->and($result['children'])->toBeArray()
        ->and($result['children'][0]['type'])->toBe('paragraph')
        ->and($result['children'][0]['content'])->toBe('This is a simple paragraph.');
});

test('it converts heading level 1 to JSON with correct level', function () {
    $markdown = '# My Heading';

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'][0]['type'])->toBe('heading')
        ->and($result['children'][0]['level'])->toBe(1)
        ->and($result['children'][0]['content'])->toBe('My Heading');
});

test('it converts heading level 2 to JSON', function () {
    $markdown = '## Section Heading';

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'][0])->toMatchArray([
        'type' => 'heading',
        'level' => 2,
        'content' => 'Section Heading',
    ]);
});

test('it converts multiple headings H1-H6', function () {
    $markdown = <<<'MD'
# Heading 1
## Heading 2
### Heading 3
#### Heading 4
##### Heading 5
###### Heading 6
MD;

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'])->toHaveCount(6);

    foreach (range(1, 6) as $level) {
        expect($result['children'][$level - 1])->toMatchArray([
            'type' => 'heading',
            'level' => $level,
            'content' => "Heading {$level}",
        ]);
    }
});

test('it converts document with mixed headings and paragraphs', function () {
    $markdown = <<<'MD'
# Main Title

This is the first paragraph.

## Subsection

Another paragraph here.
MD;

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'])->toHaveCount(4)
        ->and($result['children'][0]['type'])->toBe('heading')
        ->and($result['children'][0]['level'])->toBe(1)
        ->and($result['children'][1]['type'])->toBe('paragraph')
        ->and($result['children'][2]['type'])->toBe('heading')
        ->and($result['children'][2]['level'])->toBe(2)
        ->and($result['children'][3]['type'])->toBe('paragraph');
});

test('it converts empty string to minimal document structure', function () {
    $markdown = '';

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['type'])->toBe('document')
        ->and($result['children'])->toBeArray()
        ->and($result['children'])->toBeEmpty();
});

test('it handles malformed markdown gracefully', function () {
    $markdown = '######## Too many hashes';

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    // Should parse as paragraph since it's malformed
    expect($result['children'][0]['type'])->toBe('paragraph');
});

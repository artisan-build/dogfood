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

// Frontmatter tests
test('it parses YAML frontmatter and includes in JSON', function () {
    $markdown = <<<'MD'
---
title: My Document
date: 2025-11-03
tags:
  - example
  - markdown
---

# Content Heading

This is the content.
MD;

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result)->toHaveKey('frontmatter')
        ->and($result['frontmatter'])->toBeArray()
        ->and($result['frontmatter']['title'])->toBe('My Document')
        ->and($result['frontmatter']['date'])->toBeInt() // YAML parses dates as timestamps
        ->and($result['frontmatter']['tags'])->toBe(['example', 'markdown'])
        ->and($result['children'])->toHaveCount(2)
        ->and($result['children'][0]['type'])->toBe('heading');
});

test('it handles documents with no frontmatter', function () {
    $markdown = '# Just a heading';

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result)->not->toHaveKey('frontmatter')
        ->and($result['children'][0]['type'])->toBe('heading');
});

test('it handles complex frontmatter with nested objects', function () {
    $markdown = <<<'MD'
---
title: Complex Document
author:
  name: John Doe
  email: john@example.com
metadata:
  version: 1.0
  published: true
---

Content here.
MD;

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['frontmatter'])->toBeArray()
        ->and($result['frontmatter']['author'])->toBeArray()
        ->and($result['frontmatter']['author']['name'])->toBe('John Doe')
        ->and($result['frontmatter']['metadata']['version'])->toBeNumeric() // YAML parses numeric values
        ->and($result['frontmatter']['metadata']['published'])->toBe(true);
});

// GFM tests
test('it converts markdown tables to JSON structure', function () {
    $markdown = <<<'MD'
| Header 1 | Header 2 |
|----------|----------|
| Cell 1   | Cell 2   |
| Cell 3   | Cell 4   |
MD;

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'])->toHaveCount(1)
        ->and($result['children'][0]['type'])->toBe('table')
        ->and($result['children'][0]['header'])->toBe(['Header 1', 'Header 2'])
        ->and($result['children'][0]['rows'])->toHaveCount(2)
        ->and($result['children'][0]['rows'][0])->toBe(['Cell 1', 'Cell 2'])
        ->and($result['children'][0]['rows'][1])->toBe(['Cell 3', 'Cell 4']);
});

test('it converts task lists to JSON structure', function () {
    $markdown = <<<'MD'
- [ ] Unchecked task
- [x] Checked task
- [ ] Another unchecked task
MD;

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'])->toHaveCount(1)
        ->and($result['children'][0]['type'])->toBe('list')
        ->and($result['children'][0]['ordered'])->toBe(false)
        ->and($result['children'][0]['items'])->toHaveCount(3)
        ->and($result['children'][0]['items'][0])->toMatchArray([
            'checked' => false,
            'content' => 'Unchecked task',
        ])
        ->and($result['children'][0]['items'][1])->toMatchArray([
            'checked' => true,
            'content' => 'Checked task',
        ])
        ->and($result['children'][0]['items'][2])->toMatchArray([
            'checked' => false,
            'content' => 'Another unchecked task',
        ]);
});

test('it converts strikethrough text to JSON structure', function () {
    $markdown = 'This is ~~strikethrough~~ text.';

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'])->toHaveCount(1)
        ->and($result['children'][0]['type'])->toBe('paragraph')
        ->and($result['children'][0]['content'])->toBeArray()
        ->and($result['children'][0]['content'])->toMatchArray([
            ['type' => 'text', 'content' => 'This is '],
            ['type' => 'strikethrough', 'content' => 'strikethrough'],
            ['type' => 'text', 'content' => ' text.'],
        ]);
});

test('it converts links to JSON structure', function () {
    $markdown = 'Visit [my website](https://example.com) for more info.';

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'])->toHaveCount(1)
        ->and($result['children'][0]['type'])->toBe('paragraph')
        ->and($result['children'][0]['content'])->toBeArray()
        ->and($result['children'][0]['content'])->toMatchArray([
            ['type' => 'text', 'content' => 'Visit '],
            ['type' => 'link', 'url' => 'https://example.com', 'content' => 'my website'],
            ['type' => 'text', 'content' => ' for more info.'],
        ]);
});

// List and code block tests
test('it converts unordered lists to JSON structure', function () {
    $markdown = <<<'MD'
- Item 1
- Item 2
- Item 3
MD;

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'])->toHaveCount(1)
        ->and($result['children'][0]['type'])->toBe('list')
        ->and($result['children'][0]['ordered'])->toBe(false)
        ->and($result['children'][0]['items'])->toBe(['Item 1', 'Item 2', 'Item 3']);
});

test('it converts ordered lists to JSON structure', function () {
    $markdown = <<<'MD'
1. First item
2. Second item
3. Third item
MD;

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'])->toHaveCount(1)
        ->and($result['children'][0]['type'])->toBe('list')
        ->and($result['children'][0]['ordered'])->toBe(true)
        ->and($result['children'][0]['items'])->toBe(['First item', 'Second item', 'Third item']);
});

test('it converts fenced code blocks to JSON structure', function () {
    $markdown = <<<'MD'
```php
function hello() {
    return 'world';
}
```
MD;

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'])->toHaveCount(1)
        ->and($result['children'][0]['type'])->toBe('code')
        ->and($result['children'][0]['language'])->toBe('php')
        ->and($result['children'][0]['content'])->toBe("function hello() {\n    return 'world';\n}");
});

test('it converts indented code blocks to JSON structure', function () {
    $markdown = <<<'MD'
Regular paragraph.

    indented code
    another line
MD;

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'])->toHaveCount(2)
        ->and($result['children'][0]['type'])->toBe('paragraph')
        ->and($result['children'][1]['type'])->toBe('code')
        ->and($result['children'][1]['language'])->toBeNull()
        ->and($result['children'][1]['content'])->toBe("indented code\nanother line");
});

// Emphasis tests
test('it converts bold text to JSON structure', function () {
    $markdown = 'This is **bold** text.';

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'])->toHaveCount(1)
        ->and($result['children'][0]['type'])->toBe('paragraph')
        ->and($result['children'][0]['content'])->toBeArray()
        ->and($result['children'][0]['content'])->toMatchArray([
            ['type' => 'text', 'content' => 'This is '],
            ['type' => 'strong', 'content' => 'bold'],
            ['type' => 'text', 'content' => ' text.'],
        ]);
});

test('it converts italic text to JSON structure', function () {
    $markdown = 'This is *italic* text.';

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'])->toHaveCount(1)
        ->and($result['children'][0]['type'])->toBe('paragraph')
        ->and($result['children'][0]['content'])->toBeArray()
        ->and($result['children'][0]['content'])->toMatchArray([
            ['type' => 'text', 'content' => 'This is '],
            ['type' => 'emphasis', 'content' => 'italic'],
            ['type' => 'text', 'content' => ' text.'],
        ]);
});

test('it converts bold and italic text to JSON structure', function () {
    $markdown = 'This is ***bold italic*** text.';

    $json = MarkdownToJson::convert($markdown);
    $result = json_decode($json, true);

    expect($result['children'])->toHaveCount(1)
        ->and($result['children'][0]['type'])->toBe('paragraph')
        ->and($result['children'][0]['content'])->toBeArray()
        ->and($result['children'][0]['content'])->toMatchArray([
            ['type' => 'text', 'content' => 'This is '],
            ['type' => 'strong-emphasis', 'content' => 'bold italic'],
            ['type' => 'text', 'content' => ' text.'],
        ]);
});

<?php

declare(strict_types=1);

use ArtisanBuild\JsonMarkdown\JsonToMarkdown;

test('it converts JSON document with paragraph to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            [
                'type' => 'paragraph',
                'content' => 'This is a simple paragraph.',
            ],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    expect($markdown)->toBe('This is a simple paragraph.');
});

test('it converts JSON with heading level 1 to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            [
                'type' => 'heading',
                'level' => 1,
                'content' => 'My Heading',
            ],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    expect($markdown)->toBe('# My Heading');
});

test('it converts JSON with heading level 2 to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            [
                'type' => 'heading',
                'level' => 2,
                'content' => 'Section Heading',
            ],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    expect($markdown)->toBe('## Section Heading');
});

test('it converts JSON with multiple headings to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            ['type' => 'heading', 'level' => 1, 'content' => 'Heading 1'],
            ['type' => 'heading', 'level' => 2, 'content' => 'Heading 2'],
            ['type' => 'heading', 'level' => 3, 'content' => 'Heading 3'],
            ['type' => 'heading', 'level' => 4, 'content' => 'Heading 4'],
            ['type' => 'heading', 'level' => 5, 'content' => 'Heading 5'],
            ['type' => 'heading', 'level' => 6, 'content' => 'Heading 6'],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    $expected = <<<'MD'
# Heading 1

## Heading 2

### Heading 3

#### Heading 4

##### Heading 5

###### Heading 6
MD;

    expect($markdown)->toBe($expected);
});

test('it converts JSON with mixed headings and paragraphs to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            ['type' => 'heading', 'level' => 1, 'content' => 'Main Title'],
            ['type' => 'paragraph', 'content' => 'This is the first paragraph.'],
            ['type' => 'heading', 'level' => 2, 'content' => 'Subsection'],
            ['type' => 'paragraph', 'content' => 'Another paragraph here.'],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    $expected = <<<'MD'
# Main Title

This is the first paragraph.

## Subsection

Another paragraph here.
MD;

    expect($markdown)->toBe($expected);
});

test('it converts empty JSON document to empty string', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    expect($markdown)->toBe('');
});

// Frontmatter tests
test('it converts JSON with frontmatter to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'frontmatter' => [
            'title' => 'My Document',
            'date' => '2025-11-03',
            'tags' => ['example', 'markdown'],
        ],
        'children' => [
            ['type' => 'heading', 'level' => 1, 'content' => 'Content Heading'],
            ['type' => 'paragraph', 'content' => 'This is the content.'],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    $expected = <<<'MD'
---
title: 'My Document'
date: '2025-11-03'
tags:
  - example
  - markdown
---

# Content Heading

This is the content.
MD;

    expect($markdown)->toBe($expected);
});

test('it converts JSON with complex nested frontmatter to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'frontmatter' => [
            'title' => 'Complex Document',
            'author' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'metadata' => [
                'version' => 1.0,
                'published' => true,
            ],
        ],
        'children' => [
            ['type' => 'paragraph', 'content' => 'Content here.'],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    $expected = <<<'MD'
---
title: 'Complex Document'
author:
  name: 'John Doe'
  email: john@example.com
metadata:
  version: 1
  published: true
---

Content here.
MD;

    expect($markdown)->toBe($expected);
});

test('it handles documents without frontmatter', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            ['type' => 'heading', 'level' => 1, 'content' => 'Just a heading'],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    expect($markdown)->toBe('# Just a heading');
});

// GFM tests
test('it converts JSON table to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            [
                'type' => 'table',
                'header' => ['Header 1', 'Header 2'],
                'rows' => [
                    ['Cell 1', 'Cell 2'],
                    ['Cell 3', 'Cell 4'],
                ],
            ],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    $expected = <<<'MD'
| Header 1 | Header 2 |
|----------|----------|
| Cell 1 | Cell 2 |
| Cell 3 | Cell 4 |
MD;

    expect($markdown)->toBe($expected);
});

test('it converts JSON task list to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            [
                'type' => 'list',
                'ordered' => false,
                'items' => [
                    ['checked' => false, 'content' => 'Unchecked task'],
                    ['checked' => true, 'content' => 'Checked task'],
                    ['checked' => false, 'content' => 'Another unchecked task'],
                ],
            ],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    $expected = <<<'MD'
- [ ] Unchecked task
- [x] Checked task
- [ ] Another unchecked task
MD;

    expect($markdown)->toBe($expected);
});

test('it converts JSON with inline formatting to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            [
                'type' => 'paragraph',
                'content' => [
                    ['type' => 'text', 'content' => 'This is '],
                    ['type' => 'strikethrough', 'content' => 'strikethrough'],
                    ['type' => 'text', 'content' => ' text.'],
                ],
            ],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    expect($markdown)->toBe('This is ~~strikethrough~~ text.');
});

// Remaining features tests
test('it converts JSON with unordered list to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            [
                'type' => 'list',
                'ordered' => false,
                'items' => ['Item 1', 'Item 2', 'Item 3'],
            ],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    $expected = <<<'MD'
- Item 1
- Item 2
- Item 3
MD;

    expect($markdown)->toBe($expected);
});

test('it converts JSON with ordered list to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            [
                'type' => 'list',
                'ordered' => true,
                'items' => ['First item', 'Second item', 'Third item'],
            ],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    $expected = <<<'MD'
1. First item
2. Second item
3. Third item
MD;

    expect($markdown)->toBe($expected);
});

test('it converts JSON with fenced code block to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            [
                'type' => 'code',
                'language' => 'php',
                'content' => "function hello() {\n    return 'world';\n}",
            ],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    $expected = <<<'MD'
```php
function hello() {
    return 'world';
}
```
MD;

    expect($markdown)->toBe($expected);
});

test('it converts JSON with indented code block to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            ['type' => 'paragraph', 'content' => 'Regular paragraph.'],
            ['type' => 'code', 'language' => null, 'content' => "indented code\nanother line"],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    $expected = <<<'MD'
Regular paragraph.

    indented code
    another line
MD;

    expect($markdown)->toBe($expected);
});

test('it converts JSON with emphasis to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            [
                'type' => 'paragraph',
                'content' => [
                    ['type' => 'text', 'content' => 'This is '],
                    ['type' => 'strong', 'content' => 'bold'],
                    ['type' => 'text', 'content' => ' text.'],
                ],
            ],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    expect($markdown)->toBe('This is **bold** text.');
});

test('it converts JSON with italic to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            [
                'type' => 'paragraph',
                'content' => [
                    ['type' => 'text', 'content' => 'This is '],
                    ['type' => 'emphasis', 'content' => 'italic'],
                    ['type' => 'text', 'content' => ' text.'],
                ],
            ],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    expect($markdown)->toBe('This is *italic* text.');
});

test('it converts JSON with bold and italic to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            [
                'type' => 'paragraph',
                'content' => [
                    ['type' => 'text', 'content' => 'This is '],
                    ['type' => 'strong-emphasis', 'content' => 'bold italic'],
                    ['type' => 'text', 'content' => ' text.'],
                ],
            ],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    expect($markdown)->toBe('This is ***bold italic*** text.');
});

test('it converts JSON with link to markdown', function () {
    $json = json_encode([
        'type' => 'document',
        'children' => [
            [
                'type' => 'paragraph',
                'content' => [
                    ['type' => 'text', 'content' => 'Visit '],
                    ['type' => 'link', 'url' => 'https://example.com', 'content' => 'my website'],
                    ['type' => 'text', 'content' => ' for more info.'],
                ],
            ],
        ],
    ]);

    $markdown = JsonToMarkdown::convert($json);

    expect($markdown)->toBe('Visit [my website](https://example.com) for more info.');
});

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

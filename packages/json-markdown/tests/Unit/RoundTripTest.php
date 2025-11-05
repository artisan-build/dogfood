<?php

declare(strict_types=1);

use ArtisanBuild\JsonMarkdown\JsonToMarkdown;
use ArtisanBuild\JsonMarkdown\MarkdownDirectory;
use ArtisanBuild\JsonMarkdown\MarkdownToJson;
use Illuminate\Support\Facades\Storage;

test('simple document round-trip preserves content', function (): void {
    $original = '# Hello World';

    $json = MarkdownToJson::convert($original);
    $result = JsonToMarkdown::convert($json);

    expect($result)->toBe($original);
});

test('document with paragraphs round-trip preserves content', function (): void {
    $original = <<<'MD'
# Main Title

This is the first paragraph.

This is the second paragraph.
MD;

    $json = MarkdownToJson::convert($original);
    $result = JsonToMarkdown::convert($json);

    expect($result)->toBe($original);
});

test('complex document with multiple features round-trip preserves content', function (): void {
    $original = <<<'MD'
# Main Heading

This is a paragraph.

## Subsection

- Item 1
- Item 2
- Item 3

1. First
2. Second
3. Third
MD;

    $json = MarkdownToJson::convert($original);
    $result = JsonToMarkdown::convert($json);

    expect($result)->toBe($original);
});

test('document with frontmatter round-trip preserves content', function (): void {
    $original = <<<'MD'
---
title: 'My Document'
date: '2025-11-03'
tags:
  - example
  - test
---

# Content Heading

This is the content.
MD;

    $json = MarkdownToJson::convert($original);
    $result = JsonToMarkdown::convert($json);

    expect($result)->toBe($original);
});

test('document with code blocks round-trip preserves content', function (): void {
    $original = <<<'MD'
# Code Example

```php
function hello() {
    return 'world';
}
```

Regular text.

    indented code
    another line
MD;

    $json = MarkdownToJson::convert($original);
    $result = JsonToMarkdown::convert($json);

    expect($result)->toBe($original);
});

test('document with inline formatting round-trip preserves content', function (): void {
    $original = 'This is **bold** and this is *italic* text.';

    $json = MarkdownToJson::convert($original);
    $result = JsonToMarkdown::convert($json);

    expect($result)->toBe($original);
});

test('document with links round-trip preserves content', function (): void {
    $original = 'Visit [my website](https://example.com) for more info.';

    $json = MarkdownToJson::convert($original);
    $result = JsonToMarkdown::convert($json);

    expect($result)->toBe($original);
});

test('document with tables round-trip preserves content', function (): void {
    $original = <<<'MD'
| Header 1 | Header 2 |
|----------|----------|
| Cell 1 | Cell 2 |
| Cell 3 | Cell 4 |
MD;

    $json = MarkdownToJson::convert($original);
    $result = JsonToMarkdown::convert($json);

    expect($result)->toBe($original);
});

test('document with task lists round-trip preserves content', function (): void {
    $original = <<<'MD'
- [ ] Unchecked task
- [x] Checked task
- [ ] Another unchecked task
MD;

    $json = MarkdownToJson::convert($original);
    $result = JsonToMarkdown::convert($json);

    expect($result)->toBe($original);
});

test('directory round-trip preserves structure and content', function (): void {
    Storage::fake('local');

    // Create source files
    Storage::put('source/readme.md', '# README');
    Storage::put('source/guide.md', '# Guide');
    Storage::put('source/docs/intro.md', '# Introduction');

    $directory = new MarkdownDirectory(Storage::disk('local'));

    // Convert to JSON
    $json = $directory->toJson('source');

    // Convert back to files
    $directory->fromJson($json, 'restored');

    // Verify files match
    expect(Storage::get('restored/readme.md'))->toBe('# README')
        ->and(Storage::get('restored/guide.md'))->toBe('# Guide')
        ->and(Storage::get('restored/docs/intro.md'))->toBe('# Introduction');
});

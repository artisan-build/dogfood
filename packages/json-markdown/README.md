<p align="center"><img src="https://github.com/artisan-build/json-markdown/raw/HEAD/art/json-markdown.png" width="75%" alt="Artisan Build Package Json Markdown Logo"></p>

# Json Markdown

A Laravel package for bidirectional conversion between Markdown and JSON, supporting GitHub Flavored Markdown (GFM), frontmatter, and directory operations.

> [!WARNING]
> This package is currently under active development, and we have not yet released a major version. Once a 0.* version
> has been tagged, we strongly recommend locking your application to a specific working version because we might make
> breaking changes even in patch releases until we've tagged 1.0.

## Features

- **Bidirectional Conversion**: Convert Markdown to JSON and back again
- **GitHub Flavored Markdown**: Full support for tables, task lists, and strikethrough
- **Frontmatter Support**: Parse and generate YAML frontmatter
- **Directory Operations**: Convert entire directory structures of Markdown files
- **Round-Trip Safe**: Markdown → JSON → Markdown produces identical output
- **Laravel Integration**: Facades and service provider for easy use in Laravel applications

## Installation

```bash
composer require artisan-build/json-markdown
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=json-markdown
```

Available configuration options in `config/json-markdown.php`:

```php
return [
    // Pretty print JSON output
    'pretty_print' => true,

    // File extensions to treat as markdown
    'extensions' => ['.md', '.markdown'],

    // Overwrite existing files when converting from JSON
    'overwrite' => true,
];
```

## Usage

### Converting Single Files

#### Markdown to JSON

```php
use ArtisanBuild\JsonMarkdown\MarkdownToJson;

$markdown = '# Hello World';
$json = MarkdownToJson::convert($markdown);
```

Or use the facade:

```php
use ArtisanBuild\JsonMarkdown\Facades\MarkdownToJson;

$json = MarkdownToJson::convert($markdown);
```

#### JSON to Markdown

```php
use ArtisanBuild\JsonMarkdown\JsonToMarkdown;

$json = json_encode([
    'type' => 'document',
    'children' => [
        ['type' => 'heading', 'level' => 1, 'content' => 'Hello World']
    ]
]);

$markdown = JsonToMarkdown::convert($json);
// Returns: "# Hello World"
```

Or use the facade:

```php
use ArtisanBuild\JsonMarkdown\Facades\JsonToMarkdown;

$markdown = JsonToMarkdown::convert($json);
```

### Working with Frontmatter

```php
$markdown = <<<MD
---
title: 'My Document'
date: '2025-11-03'
tags:
  - example
  - markdown
---

# Content

This is the content.
MD;

$json = MarkdownToJson::convert($markdown);
// JSON will include 'frontmatter' property with parsed YAML

$restored = JsonToMarkdown::convert($json);
// Frontmatter is reconstructed
```

### Directory Operations

#### Convert Directory to JSON

```php
use ArtisanBuild\JsonMarkdown\MarkdownDirectory;
use Illuminate\Support\Facades\Storage;

$directory = new MarkdownDirectory(Storage::disk('local'));
$json = $directory->toJson('path/to/markdown/files');
```

Or use the facade:

```php
use ArtisanBuild\JsonMarkdown\Facades\MarkdownDirectory;

$json = MarkdownDirectory::toJson('path/to/markdown/files');
```

#### Create Directory from JSON

```php
$json = json_encode([
    'files' => [
        [
            'path' => 'readme.md',
            'content' => [
                'type' => 'document',
                'children' => [
                    ['type' => 'heading', 'level' => 1, 'content' => 'README']
                ]
            ]
        ]
    ],
    'directories' => [
        [
            'path' => 'docs',
            'contents' => [
                'files' => [
                    [
                        'path' => 'intro.md',
                        'content' => [
                            'type' => 'document',
                            'children' => [
                                ['type' => 'heading', 'level' => 1, 'content' => 'Introduction']
                            ]
                        ]
                    ]
                ],
                'directories' => []
            ]
        ]
    ]
]);

$directory = new MarkdownDirectory(Storage::disk('local'));
$directory->fromJson($json, 'output/path');
```

### Supported Markdown Features

- **Headings**: All levels (H1-H6)
- **Paragraphs**: Regular text content
- **Lists**: Ordered and unordered lists
- **Code Blocks**: Fenced (with language) and indented
- **Emphasis**: Bold, italic, and bold+italic
- **Links**: Standard markdown links
- **Tables**: GitHub Flavored Markdown tables
- **Task Lists**: Checkbox lists with `[ ]` and `[x]`
- **Strikethrough**: `~~text~~` formatting
- **Frontmatter**: YAML metadata

## JSON Structure

The JSON structure uses a simple, predictable format:

```json
{
    "type": "document",
    "frontmatter": {
        "title": "Example",
        "date": "2025-11-03"
    },
    "children": [
        {
            "type": "heading",
            "level": 1,
            "content": "Title"
        },
        {
            "type": "paragraph",
            "content": "Simple text content"
        },
        {
            "type": "list",
            "ordered": false,
            "items": ["Item 1", "Item 2"]
        }
    ]
}
```

## Testing

```bash
composer test
```

## Memberware

This package is part of our internal toolkit and is optimized for our own purposes. We do not accept issues or PRs
in this repository. 


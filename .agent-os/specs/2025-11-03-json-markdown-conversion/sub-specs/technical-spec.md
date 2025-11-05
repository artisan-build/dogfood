# Technical Specification

This is the technical specification for the spec detailed in @.agent-os/specs/2025-11-03-json-markdown-conversion/spec.md

> Created: 2025-11-03
> Version: 1.0.0

## Technical Requirements

### Core Classes

1. **MarkdownToJson** - Converts Markdown strings to JSON
   - Static `convert(string $markdown): string` method
   - Returns JSON string with document structure
   - Handles common Markdown syntax (headings, paragraphs, lists, code blocks, emphasis, links)

2. **JsonToMarkdown** - Converts JSON back to Markdown
   - Static `convert(string $json): string` method
   - Reconstructs valid Markdown from JSON structure
   - Preserves formatting and hierarchy

3. **MarkdownDirectory** - Handles directory operations
   - Constructor accepts `Filesystem` instance via dependency injection
   - `toJson(string $path): string` method to parse directory tree
   - `fromJson(string $json, string $outputPath): void` method to write directory tree
   - Recursively processes subdirectories
   - Preserves directory structure in JSON
   - Uses Laravel's `Filesystem` for all file operations

### JSON Structure

The JSON format should capture:
- Document type (e.g., "document", "heading", "paragraph", "list", "code_block", "table", "task_list")
- Content/text where applicable
- Hierarchy/nesting (parent-child relationships)
- Metadata (heading levels, list types, code language, task completion status)
- YAML frontmatter as a separate top-level property
- For directories: file paths and directory structure

**Example JSON structure for a Markdown document with frontmatter:**

```json
{
  "type": "document",
  "frontmatter": {
    "title": "My Document",
    "date": "2025-11-03",
    "tags": ["example", "markdown"]
  },
  "children": [
    {
      "type": "heading",
      "level": 1,
      "content": "My Document"
    },
    {
      "type": "paragraph",
      "content": "This is a paragraph with **bold** and *italic* text."
    },
    {
      "type": "list",
      "ordered": false,
      "items": [
        { "content": "First item" },
        { "content": "Second item" }
      ]
    },
    {
      "type": "task_list",
      "items": [
        { "content": "Completed task", "checked": true },
        { "content": "Incomplete task", "checked": false }
      ]
    },
    {
      "type": "table",
      "headers": ["Column 1", "Column 2"],
      "rows": [
        ["Cell 1", "Cell 2"],
        ["Cell 3", "Cell 4"]
      ]
    },
    {
      "type": "code_block",
      "language": "php",
      "content": "<?php\necho 'Hello';"
    }
  ]
}
```

**Example JSON structure for a directory:**

```json
{
  "type": "directory",
  "path": "/path/to/docs",
  "children": [
    {
      "type": "file",
      "name": "README.md",
      "content": {
        "type": "document",
        "children": []
      }
    },
    {
      "type": "directory",
      "name": "guides",
      "children": [
        {
          "type": "file",
          "name": "getting-started.md",
          "content": {
            "type": "document",
            "children": []
          }
        }
      ]
    }
  ]
}
```

## Approach Options

### Option A: Build Custom Parser

Build a custom Markdown parser from scratch using regex and string manipulation.

**Pros:**
- No external dependencies
- Full control over parsing logic
- Can optimize for our specific needs

**Cons:**
- Time-consuming to implement
- Difficult to handle edge cases
- Markdown parsing is a solved problem
- Higher maintenance burden

### Option B: Use league/commonmark (Selected)

Use the `league/commonmark` package, which is the de facto standard for Markdown parsing in PHP.

**Pros:**
- Battle-tested, widely used library
- Handles edge cases and complex Markdown
- Active maintenance and community support
- CommonMark specification compliant
- Extensible architecture with AST (Abstract Syntax Tree)
- Can traverse the AST to build JSON structure

**Cons:**
- Additional dependency
- Slightly larger package size
- Need to learn library API

**Rationale:** The `league/commonmark` library provides a robust, standards-compliant Markdown parser with an AST that we can traverse to build our JSON structure. This is significantly more reliable than building a custom parser and aligns with Laravel ecosystem best practices of using well-maintained dependencies.

### Option C: Use symfony/yaml + Custom Parser

Use Symfony's YAML component combined with a custom Markdown parser.

**Pros:**
- YAML might be more readable than JSON for some use cases

**Cons:**
- Requirement is specifically for JSON, not YAML
- Still requires custom Markdown parsing
- Adds unnecessary dependency

## External Dependencies

### league/commonmark

**Purpose:** Parse Markdown into an Abstract Syntax Tree (AST) that can be traversed to build JSON structures

**Version:** ^2.4

**Justification:** This is the most widely-used and standards-compliant Markdown parser in PHP. It provides a robust AST that we can traverse to build our JSON structure. Rather than reinventing Markdown parsing (which has many edge cases), we leverage this battle-tested library.

**Extensions Used:**
1. **FrontMatterExtension** - Built-in extension for parsing YAML frontmatter
2. **GithubFlavoredMarkdownExtension** - Bundle extension that enables:
   - Tables
   - Task lists
   - Strikethrough
   - Autolinks

**Usage Pattern:**
1. Configure environment with `FrontMatterExtension` and `GithubFlavoredMarkdownExtension`
2. Parse Markdown to AST using `MarkdownConverter` with configured environment
3. Access frontmatter from conversion result
4. Walk the AST nodes using `NodeWalker`
5. Build JSON structure from AST nodes including frontmatter
6. For reverse conversion, reconstruct frontmatter and Markdown from JSON

### symfony/yaml

**Purpose:** Parse YAML frontmatter (used by FrontMatterExtension)

**Version:** ^6.0|^7.0

**Justification:** Required by league/commonmark's FrontMatterExtension. Symfony's YAML component is the standard YAML parser in PHP.

## Implementation Details

### MarkdownToJson Implementation

1. Create `Environment` with `FrontMatterExtension` and `GithubFlavoredMarkdownExtension`
2. Use `MarkdownConverter` with configured environment to parse Markdown into AST
3. Extract frontmatter from `ConverterResult` (if present)
4. Walk the AST using `NodeWalker`
5. Build JSON structure by mapping AST nodes to JSON objects:
   - Handle standard nodes: headings, paragraphs, lists, code blocks, links, emphasis
   - Handle GFM nodes: tables, task lists, strikethrough
6. Include frontmatter as top-level property in JSON
7. Return JSON string with pretty-printing (if configured)

### JsonToMarkdown Implementation

1. Decode JSON string to PHP array/object
2. Extract frontmatter from JSON (if present)
3. Convert frontmatter to YAML format using `symfony/yaml`
4. Recursively build Markdown from structure:
   - Handle standard nodes: headings, paragraphs, lists, code blocks, links, emphasis
   - Handle GFM nodes: tables (with proper pipe formatting), task lists (with [x] and [ ]), strikethrough
5. Prepend YAML frontmatter block (wrapped in `---`) to Markdown
6. Return complete Markdown string

### MarkdownDirectory Implementation

1. **toJson():**
   - Use `Filesystem::allFiles($path)` to get all files in directory
   - Filter for `.md` and `.markdown` extensions using `Filesystem` methods
   - Read each file with `Filesystem::get($path)`
   - Parse each file with `MarkdownToJson::convert()`
   - Build nested JSON structure preserving directory hierarchy
   - Return JSON string

2. **fromJson():**
   - Decode JSON to PHP structure
   - Recursively process directory/file nodes
   - Create directories with `Filesystem::makeDirectory($path, $mode, $recursive = true)`
   - Write Markdown files with `Filesystem::put($path, $contents)`
   - Handle errors using Laravel's filesystem exceptions
   - Check if files exist with `Filesystem::exists($path)`
   - Overwrite behavior controlled by configuration

## Error Handling

- Invalid JSON should throw `InvalidArgumentException`
- Invalid Markdown should be handled gracefully (parse what's possible)
- File system errors use Laravel's `Illuminate\Contracts\Filesystem\FileNotFoundException`
- Missing directories in `fromJson()` should be created automatically with `makeDirectory()`
- Existing files should be overwritten when configured (default: true)
- Use `Filesystem::isReadable()` and `Filesystem::isWritable()` for permission checks

## Configuration

Add configuration options in `config/json-markdown.php`:

```php
return [
    // Whether to pretty-print JSON output
    'pretty_print' => env('JSON_MARKDOWN_PRETTY_PRINT', true),

    // File extensions to process in directory operations
    'extensions' => ['.md', '.markdown'],

    // Whether to overwrite existing files in fromJson operations
    'overwrite' => true,
];
```

## Laravel Integration

### Facades

Provide Laravel facades for convenient access:

```php
use ArtisanBuild\JsonMarkdown\Facades\MarkdownToJson;
use ArtisanBuild\JsonMarkdown\Facades\JsonToMarkdown;
use ArtisanBuild\JsonMarkdown\Facades\MarkdownDirectory;

// String conversion
$json = MarkdownToJson::convert($markdown);
$markdown = JsonToMarkdown::convert($json);

// Directory operations (facade resolves from container with Filesystem injected)
$dirJson = MarkdownDirectory::toJson('/path/to/docs');
MarkdownDirectory::fromJson($dirJson, '/path/to/output');
```

### Dependency Injection

The `MarkdownDirectory` class should be resolvable from the container:

```php
use ArtisanBuild\JsonMarkdown\MarkdownDirectory;
use Illuminate\Filesystem\Filesystem;

// Container will inject Filesystem automatically
$directory = app(MarkdownDirectory::class);
$json = $directory->toJson('/path/to/docs');
```

### Artisan Commands (Optional Future Enhancement)

Could add Artisan commands for CLI usage:

```bash
php artisan markdown:to-json input.md output.json
php artisan markdown:from-json input.json output.md
php artisan markdown:dir-to-json /docs /output.json
php artisan markdown:dir-from-json /input.json /output-dir
```

**Note:** Commands are out of scope for initial implementation but document for future consideration.

# Spec Requirements Document

> Spec: JSON-Markdown Conversion
> Created: 2025-11-03
> Status: Planning

## Overview

Implement bidirectional conversion between Markdown and JSON, including support for recursive directory operations. This package will enable developers to parse Markdown documents into structured JSON format and reconstruct Markdown files from JSON, with additional capabilities to process entire directory structures recursively.

## User Stories

### Individual File Conversion

As a developer, I want to convert a single Markdown file to JSON and back to Markdown, so that I can programmatically manipulate Markdown content using standard JSON tools and data structures.

**Workflow:**
1. Read a Markdown file from the filesystem
2. Parse it into a JSON structure that preserves document hierarchy and metadata
3. Manipulate the JSON structure programmatically
4. Convert the JSON back to valid Markdown
5. Write the result to a file

**Problem Solved:** Enables programmatic manipulation of Markdown documents without writing custom Markdown parsers.

### Directory-Based Operations

As a developer, I want to recursively convert an entire directory of Markdown files into a nested JSON structure and vice versa, so that I can work with documentation sets, content repositories, or knowledge bases as structured data.

**Workflow:**
1. Point the package at a directory containing Markdown files (potentially nested in subdirectories)
2. Parse all Markdown files into a single nested JSON structure that preserves directory structure
3. Manipulate or process the JSON structure
4. Reconstruct the entire directory structure from JSON
5. Write all files back to the filesystem

**Problem Solved:** Bulk operations on Markdown-based documentation or content systems, enabling content migrations, transformations, and analysis.

### Programmatic Usage

As a developer, I want a clean PHP API for conversion operations, so that I can integrate Markdown-JSON conversion into my Laravel application or package.

**Workflow:**
1. Use fluent PHP classes to perform conversions
2. Chain operations and apply transformations
3. Handle errors gracefully with exceptions
4. Access conversion results through type-safe interfaces

**Problem Solved:** Provides a Laravel-friendly API for Markdown manipulation without shell commands or external tools.

## Spec Scope

1. **Markdown to JSON Conversion** - Parse Markdown content into a structured JSON format that captures headings, paragraphs, lists, code blocks, links, and other common Markdown elements
2. **JSON to Markdown Conversion** - Reconstruct valid Markdown from the JSON structure
3. **YAML Frontmatter Support** - Parse and preserve YAML frontmatter from Markdown documents, include in JSON structure, and reconstruct on conversion back to Markdown
4. **GitHub Flavored Markdown (GFM)** - Support tables, task lists, strikethrough, and autolinks via league/commonmark's GithubFlavoredMarkdownExtension
5. **Single File Operations** - Convert individual files with `MarkdownToJson::convert($content)` and `JsonToMarkdown::convert($json)`
6. **Directory Parsing** - Recursively parse a directory of Markdown files into a nested JSON structure with `MarkdownDirectory::toJson($path)`
7. **Directory Writing** - Reconstruct directory structure from JSON with `MarkdownDirectory::fromJson($json, $outputPath)`

## Out of Scope

- HTML conversion (Markdown to HTML or HTML to Markdown)
- Footnotes (not commonly used, can be added as separate extension later if needed)
- Syntax highlighting or code execution
- Real-time parsing or streaming (operate on complete files/directories)
- WYSIWYG editing interfaces
- Markdown validation or linting
- Custom Markdown extensions beyond GFM (can be added in future versions)

## Expected Deliverable

1. A developer can call `MarkdownToJson::convert($markdown)` and receive a JSON string representing the document structure including frontmatter
2. A developer can call `JsonToMarkdown::convert($json)` and receive valid Markdown content with frontmatter preserved
3. A developer can call `MarkdownDirectory::toJson('/path/to/docs')` and receive a nested JSON structure representing all Markdown files in the directory
4. A developer can call `MarkdownDirectory::fromJson($json, '/path/to/output')` and the package creates a directory structure with Markdown files matching the JSON structure
5. All conversions handle CommonMark elements and GitHub Flavored Markdown (tables, task lists, strikethrough, autolinks) correctly
6. YAML frontmatter is parsed, included in JSON, and reconstructed on reverse conversion
7. The package includes comprehensive tests demonstrating all conversion scenarios including frontmatter and GFM features

## Spec Documentation

- Tasks: @.agent-os/specs/2025-11-03-json-markdown-conversion/tasks.md
- Technical Specification: @.agent-os/specs/2025-11-03-json-markdown-conversion/sub-specs/technical-spec.md
- Tests Specification: @.agent-os/specs/2025-11-03-json-markdown-conversion/sub-specs/tests.md

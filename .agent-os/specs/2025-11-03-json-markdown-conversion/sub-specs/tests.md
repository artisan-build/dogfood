# Tests Specification

This is the tests coverage details for the spec detailed in @.agent-os/specs/2025-11-03-json-markdown-conversion/spec.md

> Created: 2025-11-03
> Version: 1.0.0

## Test Coverage

### Unit Tests

**MarkdownToJsonTest**
- Converts simple paragraph to JSON structure
- Converts heading (H1-H6) to JSON with correct level
- Converts unordered list to JSON with correct items
- Converts ordered list to JSON with correct items
- Converts code block to JSON with language metadata
- Converts bold text to JSON structure
- Converts italic text to JSON structure
- Converts links to JSON with URL and text
- Converts complex nested structures correctly
- Converts empty string to minimal document structure
- Handles malformed Markdown gracefully
- **Parses YAML frontmatter and includes in JSON**
- **Handles documents with no frontmatter**
- **Converts tables to JSON with headers and rows**
- **Converts task lists with checked/unchecked state**
- **Converts strikethrough text correctly**
- **Converts autolinks to link structures**

**JsonToMarkdownTest**
- Converts JSON document structure to valid Markdown
- Converts headings from JSON with correct level syntax
- Converts paragraphs from JSON to Markdown
- Converts unordered lists from JSON to Markdown syntax
- Converts ordered lists from JSON to Markdown syntax
- Converts code blocks from JSON with language annotation
- Converts bold and italic emphasis correctly
- Converts links from JSON to Markdown link syntax
- Reconstructs complex nested structures correctly
- Handles minimal JSON structure
- Throws exception for invalid JSON
- Throws exception for malformed JSON structure
- **Reconstructs YAML frontmatter block with `---` delimiters**
- **Handles JSON with no frontmatter property**
- **Converts table JSON to proper Markdown table syntax with pipes**
- **Converts task list JSON to `[ ]` and `[x]` syntax**
- **Converts strikethrough to `~~text~~` syntax**
- **Preserves frontmatter field types (strings, arrays, objects)**

**MarkdownDirectoryTest**
- Converts single Markdown file in directory to JSON
- Converts multiple files in flat directory to JSON
- Converts nested directory structure to JSON recursively
- Preserves directory hierarchy in JSON structure
- Filters only `.md` and `.markdown` files
- Handles empty directories
- Handles directories with no Markdown files
- Throws exception for non-existent directory path

**JsonToDirectoryTest**
- Creates directory structure from JSON
- Writes single Markdown file from JSON
- Writes multiple files from JSON
- Creates nested directories from JSON
- Overwrites existing files when configured
- Creates missing parent directories automatically
- Throws exception for invalid JSON structure
- Throws exception when output path is not writable
- Handles empty directory structure JSON

### Integration Tests

**RoundTripConversionTest**
- Markdown → JSON → Markdown produces identical result for simple documents
- Markdown → JSON → Markdown preserves complex formatting
- Markdown → JSON → Markdown preserves frontmatter exactly
- Markdown → JSON → Markdown preserves GFM features (tables, task lists)
- Directory → JSON → Directory recreates identical file structure
- Directory → JSON → Directory preserves all file contents including frontmatter

**DirectoryOperationsTest**
- Can parse real-world documentation directory structure
- Can recreate directory from JSON in new location
- Handles symbolic links appropriately (skip or follow)
- Preserves file modification order in JSON

### Mocking Requirements

**File System Operations**
- Use Laravel's `Storage::fake()` for testing filesystem operations
- Mock `Filesystem` instance in unit tests when needed
- Use `Storage::fake()` to create isolated filesystem for tests
- Verify file operations with `Storage::assertExists()`, `Storage::assertMissing()`
- No need to mock individual file functions - use Laravel's fake filesystem

**External Dependencies**
- No mocking needed for `league/commonmark` - test with real library
- Configuration values can be mocked using Laravel's `Config::set()` in tests
- Inject mocked `Filesystem` instance for unit testing `MarkdownDirectory`

## Test Data

### Sample Markdown Documents

Create fixture files in `tests/fixtures/markdown/`:

1. **simple.md** - Basic document with headings, paragraphs, and a list
2. **complex.md** - Document with nested lists, code blocks, links, and emphasis
3. **minimal.md** - Single paragraph
4. **empty.md** - Empty file
5. **headings.md** - All heading levels H1-H6
6. **code-blocks.md** - Multiple code blocks with different languages
7. **lists.md** - Nested ordered and unordered lists
8. **with-frontmatter.md** - Document with YAML frontmatter including strings, arrays, and nested objects
9. **no-frontmatter.md** - Document without any frontmatter
10. **tables.md** - Document with tables of various complexities
11. **task-lists.md** - Document with completed and incomplete task lists
12. **gfm-features.md** - Document showcasing all GFM features (tables, tasks, strikethrough, autolinks)

### Sample JSON Structures

Create fixture files in `tests/fixtures/json/`:

1. **simple.json** - JSON representation of simple.md
2. **complex.json** - JSON representation of complex.md
3. **invalid.json** - Intentionally malformed JSON for error testing
4. **minimal.json** - Minimal valid document structure
5. **with-frontmatter.json** - JSON with frontmatter property containing various data types
6. **no-frontmatter.json** - JSON document structure with no frontmatter property
7. **table.json** - JSON representation of a table structure
8. **task-list.json** - JSON representation of task list with checked states
9. **gfm-features.json** - Complete GFM features in JSON format

### Sample Directories

Create fixture directories in `tests/fixtures/directories/`:

1. **flat/** - Single directory with 3 Markdown files
2. **nested/** - Multi-level directory structure with Markdown files
3. **mixed/** - Directory with Markdown and non-Markdown files
4. **empty/** - Empty directory

## Test Strategies

### Testing Markdown Parsing

- Use `league/commonmark` as-is (it's well-tested upstream)
- Focus tests on our JSON structure creation
- Verify node types are correctly identified
- Verify hierarchy is preserved
- Verify content is extracted correctly

### Testing JSON Reconstruction

- Verify each JSON node type generates correct Markdown syntax
- Test edge cases (empty content, special characters)
- Ensure whitespace and formatting rules are followed

### Testing Directory Operations

- Use `Storage::fake()` to create isolated test filesystem
- Use `Storage::fake()->put()` to create test files
- Use `Storage::fake()->makeDirectory()` to create test directory structures
- Verify operations with `Storage::assertExists()` and `Storage::get()`
- No cleanup needed - fake storage is automatically isolated per test
- For integration tests, can optionally use real filesystem with `Storage::path()` and temp directories

### Testing Error Conditions

- Invalid paths - test with non-existent paths
- Permission denied scenarios - mock `Filesystem::isReadable()` and `isWritable()` to return false
- Invalid JSON structures - pass malformed JSON strings
- Malformed Markdown (should handle gracefully) - test with edge cases
- Filesystem exceptions - mock `Filesystem` to throw exceptions for error testing

## Performance Considerations

- Add benchmarks for large documents (10,000+ lines)
- Add benchmarks for large directory trees (1,000+ files)
- Ensure no memory leaks when processing large structures
- Consider streaming for very large files (future enhancement)

## Pest Configuration

Use Pest's architecture testing to ensure:
- All public methods have return types
- All classes follow PSR-12 standards
- No debug statements in production code
- All classes have appropriate docblocks

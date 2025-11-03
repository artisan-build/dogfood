# Spec Tasks

These are the tasks to be completed for the spec detailed in @.agent-os/specs/2025-11-03-json-markdown-conversion/spec.md

> Created: 2025-11-03
> Status: Ready for Implementation

## Tasks

- [x] 1. Set up package dependencies and configuration
  - [x] 1.1 Add `league/commonmark` ^2.4 to composer.json
  - [x] 1.2 Add `symfony/yaml` ^6.0|^7.0 to composer.json
  - [x] 1.3 Run `composer update` to install dependencies
  - [x] 1.4 Update config/json-markdown.php with pretty_print, extensions, and overwrite options
  - [x] 1.5 Verify all tests pass after dependency installation

- [x] 2. Implement MarkdownToJson class with core conversion
  - [x] 2.1 Write tests for MarkdownToJson with simple documents (paragraphs, headings)
  - [x] 2.2 Create MarkdownToJson class with static convert() method
  - [x] 2.3 Configure Environment with FrontMatterExtension and GithubFlavoredMarkdownExtension
  - [x] 2.4 Implement AST parsing and node walking with NodeWalker
  - [x] 2.5 Build JSON structure from AST nodes (document, heading, paragraph)
  - [x] 2.6 Implement JSON pretty-printing based on configuration
  - [x] 2.7 Verify tests pass for basic document structures

- [x] 3. Add frontmatter support to MarkdownToJson
  - [x] 3.1 Write tests for documents with YAML frontmatter
  - [x] 3.2 Write tests for documents without frontmatter
  - [x] 3.3 Extract frontmatter from ConverterResult
  - [x] 3.4 Include frontmatter as top-level property in JSON
  - [x] 3.5 Handle various frontmatter data types (strings, arrays, objects)
  - [x] 3.6 Verify all frontmatter tests pass

- [x] 4. Add GFM support to MarkdownToJson
  - [x] 4.1 Write tests for tables conversion
  - [x] 4.2 Write tests for task lists with checked/unchecked states
  - [x] 4.3 Write tests for strikethrough text
  - [x] 4.4 Write tests for links
  - [x] 4.5 Implement table node handling in AST walker
  - [x] 4.6 Implement task list node handling with checked property
  - [x] 4.7 Implement strikethrough node handling
  - [x] 4.8 Implement link node handling
  - [x] 4.9 Verify all GFM tests pass

- [x] 5. Add list and code block support to MarkdownToJson
  - [x] 5.1 Write tests for ordered and unordered lists
  - [x] 5.2 Write tests for code blocks with language metadata
  - [x] 5.3 Implement list node handling (ordered and unordered)
  - [x] 5.4 Implement code block node handling (fenced and indented)
  - [x] 5.5 Verify all list and code block tests pass

- [x] 6. Add emphasis and link support to MarkdownToJson
  - [x] 6.1 Write tests for bold and italic text
  - [x] 6.2 Write tests for links with URL and text
  - [x] 6.3 Implement emphasis node handling (bold, italic, bold+italic)
  - [x] 6.4 Implement link node handling with URL and text extraction
  - [x] 6.5 Verify all emphasis and link tests pass

- [x] 7. Implement JsonToMarkdown class with core reconstruction
  - [x] 7.1 Write tests for basic JSON to Markdown conversion
  - [x] 7.2 Create JsonToMarkdown class with static convert() method
  - [x] 7.3 Implement JSON decoding with error handling
  - [x] 7.4 Implement recursive Markdown building from JSON structure
  - [x] 7.5 Handle document, heading, and paragraph node types
  - [x] 7.6 Verify tests pass for basic reconstructions

- [x] 8. Add frontmatter reconstruction to JsonToMarkdown
  - [x] 8.1 Write tests for frontmatter reconstruction with various data types
  - [x] 8.2 Write tests for JSON without frontmatter property
  - [x] 8.3 Extract frontmatter from JSON structure
  - [x] 8.4 Convert frontmatter to YAML using symfony/yaml
  - [x] 8.5 Prepend YAML frontmatter block with --- delimiters
  - [x] 8.6 Verify all frontmatter reconstruction tests pass

- [x] 9. Add GFM reconstruction to JsonToMarkdown
  - [x] 9.1 Write tests for table reconstruction with pipe syntax
  - [x] 9.2 Write tests for task list reconstruction with [ ] and [x]
  - [x] 9.3 Write tests for strikethrough reconstruction with ~~text~~
  - [x] 9.4 Implement table Markdown generation from JSON
  - [x] 9.5 Implement task list Markdown generation with checkbox syntax
  - [x] 9.6 Implement strikethrough Markdown generation
  - [x] 9.7 Verify all GFM reconstruction tests pass

- [x] 10. Add list, code block, emphasis, and link reconstruction to JsonToMarkdown
  - [x] 10.1 Write tests for list reconstruction (ordered and unordered)
  - [x] 10.2 Write tests for code block reconstruction with language
  - [x] 10.3 Write tests for emphasis reconstruction (bold, italic)
  - [x] 10.4 Write tests for link reconstruction
  - [x] 10.5 Implement list Markdown generation
  - [x] 10.6 Implement code block Markdown generation with language annotation
  - [x] 10.7 Implement emphasis Markdown generation
  - [x] 10.8 Implement link Markdown generation
  - [x] 10.9 Verify all reconstruction tests pass

- [x] 11. Implement MarkdownDirectory class for directory operations
  - [x] 11.1 Write tests for single file directory conversion
  - [x] 11.2 Write tests for flat directory with multiple files
  - [x] 11.3 Write tests for nested directory structures
  - [x] 11.4 Create MarkdownDirectory class with Filesystem injection
  - [x] 11.5 Implement toJson() method using Filesystem::allFiles()
  - [x] 11.6 Filter files by .md and .markdown extensions
  - [x] 11.7 Build nested JSON structure preserving directory hierarchy
  - [x] 11.8 Verify directory parsing tests pass

- [x] 12. Implement directory writing functionality
  - [x] 12.1 Write tests for fromJson() creating directory structures
  - [x] 12.2 Write tests for file creation from JSON
  - [x] 12.3 Write tests for overwrite behavior
  - [x] 12.4 Write tests for permission errors
  - [x] 12.5 Implement fromJson() method with JSON decoding
  - [x] 12.6 Recursively process directory/file nodes from JSON
  - [x] 12.7 Create directories with Filesystem::makeDirectory()
  - [x] 12.8 Write files with Filesystem::put()
  - [x] 12.9 Handle overwrite configuration
  - [x] 12.10 Verify all directory writing tests pass

- [x] 13. Create Laravel facades and service provider integration
  - [x] 13.1 Create MarkdownToJson facade
  - [x] 13.2 Create JsonToMarkdown facade
  - [x] 13.3 Create MarkdownDirectory facade
  - [x] 13.4 Update JsonMarkdownServiceProvider to bind MarkdownDirectory to container
  - [x] 13.5 Register facades in service provider
  - [x] 13.6 Write tests verifying facades work correctly
  - [x] 13.7 Verify all facade tests pass

- [x] 14. Add round-trip conversion tests
  - [x] 14.1 Write tests for Markdown → JSON → Markdown preserving simple documents
  - [x] 14.2 Write tests for Markdown → JSON → Markdown preserving complex formatting
  - [x] 14.3 Write tests for frontmatter preservation through round-trip
  - [x] 14.4 Write tests for GFM features preservation (tables, task lists)
  - [x] 14.5 Write tests for directory round-trip conversions
  - [x] 14.6 Verify all round-trip tests pass

- [x] 15. Create test fixtures for comprehensive testing
  - [x] 15.1 Create tests/fixtures/markdown/ directory with sample files
  - [x] 15.2 Create simple.md, complex.md, minimal.md, empty.md
  - [x] 15.3 Create headings.md, code-blocks.md, lists.md
  - [x] 15.4 Create with-frontmatter.md and no-frontmatter.md
  - [x] 15.5 Create tables.md, task-lists.md, gfm-features.md
  - [x] 15.6 Create corresponding JSON fixtures in tests/fixtures/json/
  - [x] 15.7 Create sample directories in tests/fixtures/directories/
  - [x] 15.8 Verify all fixtures are used in tests

- [x] 16. Error handling and edge cases
  - [x] 16.1 Write tests for invalid JSON input
  - [x] 16.2 Write tests for malformed JSON structure
  - [x] 16.3 Write tests for non-existent directory paths
  - [x] 16.4 Write tests for filesystem permission errors
  - [x] 16.5 Implement InvalidArgumentException for invalid JSON
  - [x] 16.6 Implement graceful handling of malformed Markdown
  - [x] 16.7 Add proper exception messages for all error conditions
  - [x] 16.8 Verify all error handling tests pass

- [x] 17. Documentation and final polish
  - [x] 17.1 Update package README.md with installation instructions
  - [x] 17.2 Add usage examples to README for MarkdownToJson
  - [x] 17.3 Add usage examples to README for JsonToMarkdown
  - [x] 17.4 Add usage examples to README for MarkdownDirectory
  - [x] 17.5 Document frontmatter and GFM support
  - [x] 17.6 Add API documentation to all public methods
  - [x] 17.7 Run `composer ready` and ensure all checks pass
  - [x] 17.8 Verify all tests pass

# Spec Tasks

These are the tasks to be completed for the spec detailed in @.agent-os/specs/2025-11-03-json-markdown-conversion/spec.md

> Created: 2025-11-03
> Status: Ready for Implementation

## Tasks

- [ ] 1. Set up package dependencies and configuration
  - [ ] 1.1 Add `league/commonmark` ^2.4 to composer.json
  - [ ] 1.2 Add `symfony/yaml` ^6.0|^7.0 to composer.json
  - [ ] 1.3 Run `composer update` to install dependencies
  - [ ] 1.4 Update config/json-markdown.php with pretty_print, extensions, and overwrite options
  - [ ] 1.5 Verify all tests pass after dependency installation

- [ ] 2. Implement MarkdownToJson class with core conversion
  - [ ] 2.1 Write tests for MarkdownToJson with simple documents (paragraphs, headings)
  - [ ] 2.2 Create MarkdownToJson class with static convert() method
  - [ ] 2.3 Configure Environment with FrontMatterExtension and GithubFlavoredMarkdownExtension
  - [ ] 2.4 Implement AST parsing and node walking with NodeWalker
  - [ ] 2.5 Build JSON structure from AST nodes (document, heading, paragraph)
  - [ ] 2.6 Implement JSON pretty-printing based on configuration
  - [ ] 2.7 Verify tests pass for basic document structures

- [ ] 3. Add frontmatter support to MarkdownToJson
  - [ ] 3.1 Write tests for documents with YAML frontmatter
  - [ ] 3.2 Write tests for documents without frontmatter
  - [ ] 3.3 Extract frontmatter from ConverterResult
  - [ ] 3.4 Include frontmatter as top-level property in JSON
  - [ ] 3.5 Handle various frontmatter data types (strings, arrays, objects)
  - [ ] 3.6 Verify all frontmatter tests pass

- [ ] 4. Add GFM support to MarkdownToJson
  - [ ] 4.1 Write tests for tables conversion
  - [ ] 4.2 Write tests for task lists with checked/unchecked states
  - [ ] 4.3 Write tests for strikethrough text
  - [ ] 4.4 Write tests for autolinks
  - [ ] 4.5 Implement table node handling in AST walker
  - [ ] 4.6 Implement task list node handling with checked property
  - [ ] 4.7 Implement strikethrough node handling
  - [ ] 4.8 Implement autolink node handling
  - [ ] 4.9 Verify all GFM tests pass

- [ ] 5. Add list and code block support to MarkdownToJson
  - [ ] 5.1 Write tests for ordered and unordered lists
  - [ ] 5.2 Write tests for nested lists
  - [ ] 5.3 Write tests for code blocks with language metadata
  - [ ] 5.4 Implement list node handling (ordered and unordered)
  - [ ] 5.5 Implement code block node handling with language extraction
  - [ ] 5.6 Verify all list and code block tests pass

- [ ] 6. Add emphasis and link support to MarkdownToJson
  - [ ] 6.1 Write tests for bold and italic text
  - [ ] 6.2 Write tests for links with URL and text
  - [ ] 6.3 Implement emphasis node handling (bold, italic)
  - [ ] 6.4 Implement link node handling with URL and text extraction
  - [ ] 6.5 Verify all emphasis and link tests pass

- [ ] 7. Implement JsonToMarkdown class with core reconstruction
  - [ ] 7.1 Write tests for basic JSON to Markdown conversion
  - [ ] 7.2 Create JsonToMarkdown class with static convert() method
  - [ ] 7.3 Implement JSON decoding with error handling
  - [ ] 7.4 Implement recursive Markdown building from JSON structure
  - [ ] 7.5 Handle document, heading, and paragraph node types
  - [ ] 7.6 Verify tests pass for basic reconstructions

- [ ] 8. Add frontmatter reconstruction to JsonToMarkdown
  - [ ] 8.1 Write tests for frontmatter reconstruction with various data types
  - [ ] 8.2 Write tests for JSON without frontmatter property
  - [ ] 8.3 Extract frontmatter from JSON structure
  - [ ] 8.4 Convert frontmatter to YAML using symfony/yaml
  - [ ] 8.5 Prepend YAML frontmatter block with --- delimiters
  - [ ] 8.6 Verify all frontmatter reconstruction tests pass

- [ ] 9. Add GFM reconstruction to JsonToMarkdown
  - [ ] 9.1 Write tests for table reconstruction with pipe syntax
  - [ ] 9.2 Write tests for task list reconstruction with [ ] and [x]
  - [ ] 9.3 Write tests for strikethrough reconstruction with ~~text~~
  - [ ] 9.4 Implement table Markdown generation from JSON
  - [ ] 9.5 Implement task list Markdown generation with checkbox syntax
  - [ ] 9.6 Implement strikethrough Markdown generation
  - [ ] 9.7 Verify all GFM reconstruction tests pass

- [ ] 10. Add list, code block, emphasis, and link reconstruction to JsonToMarkdown
  - [ ] 10.1 Write tests for list reconstruction (ordered and unordered)
  - [ ] 10.2 Write tests for code block reconstruction with language
  - [ ] 10.3 Write tests for emphasis reconstruction (bold, italic)
  - [ ] 10.4 Write tests for link reconstruction
  - [ ] 10.5 Implement list Markdown generation
  - [ ] 10.6 Implement code block Markdown generation with language annotation
  - [ ] 10.7 Implement emphasis Markdown generation
  - [ ] 10.8 Implement link Markdown generation
  - [ ] 10.9 Verify all reconstruction tests pass

- [ ] 11. Implement MarkdownDirectory class for directory operations
  - [ ] 11.1 Write tests for single file directory conversion
  - [ ] 11.2 Write tests for flat directory with multiple files
  - [ ] 11.3 Write tests for nested directory structures
  - [ ] 11.4 Create MarkdownDirectory class with Filesystem injection
  - [ ] 11.5 Implement toJson() method using Filesystem::allFiles()
  - [ ] 11.6 Filter files by .md and .markdown extensions
  - [ ] 11.7 Build nested JSON structure preserving directory hierarchy
  - [ ] 11.8 Verify directory parsing tests pass

- [ ] 12. Implement directory writing functionality
  - [ ] 12.1 Write tests for fromJson() creating directory structures
  - [ ] 12.2 Write tests for file creation from JSON
  - [ ] 12.3 Write tests for overwrite behavior
  - [ ] 12.4 Write tests for permission errors
  - [ ] 12.5 Implement fromJson() method with JSON decoding
  - [ ] 12.6 Recursively process directory/file nodes from JSON
  - [ ] 12.7 Create directories with Filesystem::makeDirectory()
  - [ ] 12.8 Write files with Filesystem::put()
  - [ ] 12.9 Handle overwrite configuration
  - [ ] 12.10 Verify all directory writing tests pass

- [ ] 13. Create Laravel facades and service provider integration
  - [ ] 13.1 Create MarkdownToJson facade
  - [ ] 13.2 Create JsonToMarkdown facade
  - [ ] 13.3 Create MarkdownDirectory facade
  - [ ] 13.4 Update JsonMarkdownServiceProvider to bind MarkdownDirectory to container
  - [ ] 13.5 Register facades in service provider
  - [ ] 13.6 Write tests verifying facades work correctly
  - [ ] 13.7 Verify all facade tests pass

- [ ] 14. Add round-trip conversion tests
  - [ ] 14.1 Write tests for Markdown → JSON → Markdown preserving simple documents
  - [ ] 14.2 Write tests for Markdown → JSON → Markdown preserving complex formatting
  - [ ] 14.3 Write tests for frontmatter preservation through round-trip
  - [ ] 14.4 Write tests for GFM features preservation (tables, task lists)
  - [ ] 14.5 Write tests for directory round-trip conversions
  - [ ] 14.6 Verify all round-trip tests pass

- [ ] 15. Create test fixtures for comprehensive testing
  - [ ] 15.1 Create tests/fixtures/markdown/ directory with sample files
  - [ ] 15.2 Create simple.md, complex.md, minimal.md, empty.md
  - [ ] 15.3 Create headings.md, code-blocks.md, lists.md
  - [ ] 15.4 Create with-frontmatter.md and no-frontmatter.md
  - [ ] 15.5 Create tables.md, task-lists.md, gfm-features.md
  - [ ] 15.6 Create corresponding JSON fixtures in tests/fixtures/json/
  - [ ] 15.7 Create sample directories in tests/fixtures/directories/
  - [ ] 15.8 Verify all fixtures are used in tests

- [ ] 16. Error handling and edge cases
  - [ ] 16.1 Write tests for invalid JSON input
  - [ ] 16.2 Write tests for malformed JSON structure
  - [ ] 16.3 Write tests for non-existent directory paths
  - [ ] 16.4 Write tests for filesystem permission errors
  - [ ] 16.5 Implement InvalidArgumentException for invalid JSON
  - [ ] 16.6 Implement graceful handling of malformed Markdown
  - [ ] 16.7 Add proper exception messages for all error conditions
  - [ ] 16.8 Verify all error handling tests pass

- [ ] 17. Documentation and final polish
  - [ ] 17.1 Update package README.md with installation instructions
  - [ ] 17.2 Add usage examples to README for MarkdownToJson
  - [ ] 17.3 Add usage examples to README for JsonToMarkdown
  - [ ] 17.4 Add usage examples to README for MarkdownDirectory
  - [ ] 17.5 Document frontmatter and GFM support
  - [ ] 17.6 Add API documentation to all public methods
  - [ ] 17.7 Run `composer ready` and ensure all checks pass
  - [ ] 17.8 Verify all tests pass

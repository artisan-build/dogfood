# Tests Specification

This is the tests coverage details for the spec detailed in @.agent-os/specs/2025-11-01-agent-os-web-viewer/spec.md

> Created: 2025-11-01
> Version: 1.0.0

## Test Coverage

### Unit Tests

**AgentOsSearchService**
- Test search finds exact text matches in markdown files
- Test search returns correct file paths and line numbers
- Test search extracts context snippets around matches
- Test search handles multi-word phrase queries with quotes
- Test search ranks results by number of matches
- Test search limits results to maximum of 50 matches
- Test search handles missing .agent-os directory gracefully
- Test search handles empty search queries
- Test search is case-insensitive

**Markdown Rendering**
- Test CommonMark converts markdown to HTML correctly
- Test GitHub Flavored Markdown features (tables, task lists) render properly
- Test @ reference links are converted to internal navigation links
- Test syntax highlighting classes are added to code blocks
- Test malicious HTML is escaped properly

**File System Scanner**
- Test DirectoryIterator recursively scans .agent-os folder
- Test scanner includes README.md from project root
- Test scanner merges additional configured directories
- Test scanner parses spec folder names to extract date and title
- Test scanner sorts specs in reverse chronological order
- Test scanner converts kebab-case spec names to Title Case
- Test scanner excludes non-markdown files
- Test scanner handles deeply nested directory structures
- Test scanner handles symlinks safely
- Test scanner handles missing directories gracefully

**Product Folder Concatenation Logic**
- Test concatenate all files in .agent-os/product/ folder
- Test concatenation order: mission.md, roadmap.md, tech-stack.md, decisions.md
- Test mission.md heading is kept, other top-level headings are stripped
- Test section headers are generated from filenames
- Test horizontal rules are added between sections
- Test concatenated content is cached properly
- Test missing product files don't break concatenation

**Spec Concatenation Logic**
- Test concatenate all files in spec directory (spec.md + sub-specs/*.md + tasks.md)
- Test spec.md appears first in concatenated view
- Test sub-specs files are sorted alphabetically and appear after spec.md
- Test tasks.md appears last if it exists
- Test section headers are generated from filenames
- Test horizontal rules are added between sections
- Test top-level headings are stripped from sub-files
- Test missing tasks.md doesn't break concatenation
- Test concatenated content is cached properly

### Integration Tests

**AgentOsViewer Component**
- Test component renders flat sidebar navigation with grouped sections
- Test index route displays concatenated Product folder view by default
- Test clicking Product in sidebar displays concatenated product folder view
- Test clicking spec in sidebar displays concatenated unified spec view
- Test clicking README in sidebar displays single README.md file view
- Test markdown content is rendered as HTML
- Test @ reference links navigate to correct files
- Test component handles missing files gracefully
- Test component respects middleware configuration
- Test sidebar groups items correctly (Product, Specs, Additional Dirs, README)
- Test specs appear in reverse chronological order in sidebar
- Test active navigation item is highlighted

**SidebarNavigation Component**
- Test sidebar displays "Product" item at top
- Test sidebar displays specs in reverse chronological order
- Test sidebar displays additional directories section if configured
- Test sidebar displays README at bottom
- Test sidebar navigation items are clickable links
- Test active item has visual indicator
- Test sidebar collapses on mobile
- Test spec titles are formatted correctly (kebab-case to Title Case)
- Test Product item is highlighted/active on index route

**SearchResults Component**
- Test typing in search box triggers search
- Test search results display with highlighted matches
- Test search results include README.md matches
- Test search results show which directory/section match is from
- Test clicking search result navigates to file
- Test search shows "no results" message when appropriate
- Test search loading state displays while searching
- Test search results show file path and context snippet


**Route and Middleware**
- Test default /agent-os route is registered when package installed
- Test route prefix can be customized via AGENT_OS_ROUTE_PREFIX env variable
- Test custom route prefixes work correctly (e.g., /documentation, /docs)
- Test route requires authentication when middleware configured
- Test route allows access in local environment by default
- Test custom gate is checked when configured
- Test index route displays concatenated Product folder view by default
- Test default_view config can be changed to 'readme' to show README.md instead

### Feature Tests

**Documentation Browsing**
- Test stakeholder can navigate to configured route prefix and see sidebar navigation
- Test index route displays concatenated Product folder view by default
- Test Product view includes mission, roadmap, tech-stack, and decisions in correct order
- Test Product view has proper section headers and separators
- Test clicking on README in sidebar displays formatted README document
- Test clicking on spec in sidebar displays concatenated unified view with all sections
- Test unified spec view includes spec.md, all sub-specs, and tasks in correct order
- Test unified spec view has proper section headers and separators
- Test markdown tables and task lists render correctly
- Test code blocks have syntax highlighting
- Test additional configured directories appear in sidebar navigation
- Test navigation between Product, specs, and README works smoothly

**Search Workflow**
- Test user can search for "database" and see results from database-schema.md files
- Test search includes matches from README.md
- Test search includes matches from additional configured directories
- Test multi-word search "feature flag" returns phrase matches only
- Test search highlights matched text in results
- Test search results show which section/directory each match is from
- Test clicking search result opens the file at the correct location
- Test search works across all doc types (mission, roadmap, specs, decisions)

**Access Control**
- Test unauthenticated user is redirected when auth required
- Test authenticated user can access viewer when using basic auth
- Test custom gate prevents access when gate check fails
- Test viewer is disabled when config sets enabled=false

### Mocking Requirements

**File System:**
- Create temporary .agent-os directory structure with sample markdown files for testing
- Use `Storage::fake()` or create test files in `tests/fixtures/` directory
- Clean up test files after each test run

**External Services:**
- No external services to mock (all functionality is file-based)

**Livewire Components:**
- Use Livewire's testing utilities: `Livewire::test(ComponentName::class)`
- Mock component properties and events for isolation testing
- Use `->assertSee()`, `->assertSet()`, and `->call()` for component assertions

## Test Organization

### Directory Structure
```
tests/
├── Unit/
│   ├── AgentOsSearchServiceTest.php
│   ├── MarkdownRendererTest.php
│   └── FileSystemScannerTest.php
├── Feature/
│   ├── AgentOsViewerTest.php
│   ├── SearchResultsTest.php
│   ├── FileTreeTest.php
│   └── AccessControlTest.php
├── fixtures/
│   └── .agent-os/
│       ├── product/
│       │   ├── mission.md
│       │   └── roadmap.md
│       └── specs/
│           └── 2025-01-01-sample-spec/
│               ├── spec.md
│               └── tasks.md
└── Pest.php
```

### Test Data Requirements

**Sample Markdown Files:**
- Create realistic but minimal .agent-os folder structure in fixtures
- Include examples with @ reference links to test link conversion
- Include markdown with code blocks, tables, and task lists
- Include files with search target terms for search testing

**Edge Cases to Cover:**
- Empty markdown files
- Very large markdown files (>1MB)
- Files with special characters in names
- Deeply nested directory structures (5+ levels)
- Malformed markdown (should still render safely)

## Performance Testing

**Search Performance:**
- Test search completes in under 1 second for 100 markdown files
- Test search handles timeout gracefully for very large document sets
- Verify caching improves subsequent search performance

**Rendering Performance:**
- Test large markdown files (>10,000 lines) render without timeout
- Test file tree with 50+ files renders in under 500ms
- Verify lazy loading prevents loading all files at once

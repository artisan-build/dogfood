# Tests Specification

This is the tests coverage details for the spec detailed in @.agent-os/specs/2025-10-28-url-embed-transformation/spec.md

> Created: 2025-10-28
> Version: 1.0.0

## Test Coverage

### Unit Tests

**UrlParser**
- Test YouTube URL detection (standard, shortened, embedded URLs)
- Test Vimeo URL detection (standard, player URLs)
- Test GitHub Gist URL detection (with and without file fragments)
- Test generic URL detection (fallback case)
- Test URL validation (malformed URLs rejected)
- Test HTTPS and HTTP protocol handling

**MetadataFetcher**
- Test OpenGraph tag extraction from HTML
- Test fallback to standard meta tags
- Test HTML title extraction
- Test first text block extraction for description
- Test handling of missing metadata
- Test timeout behavior
- Test HTTP error handling (404, 500)

**CacheManager**
- Test metadata caching with default TTL
- Test cache retrieval on subsequent requests
- Test cache key generation from URLs
- Test cache bypass when disabled via config
- Test TTL override from configuration

**YouTubeService**
- Test video ID extraction from standard URLs
- Test video ID extraction from shortened URLs (youtu.be)
- Test video ID extraction from embedded URLs
- Test embed HTML generation
- Test aspect ratio application

**VimeoService**
- Test video ID extraction from standard URLs
- Test video ID extraction from player URLs
- Test embed HTML generation
- Test aspect ratio application

**GitHubGistService**
- Test gist ID extraction from URLs
- Test gist ID with file fragment handling
- Test embed script tag generation

**OpenGraphService**
- Test card generation with complete OpenGraph data
- Test card generation with partial OpenGraph data (missing image)
- Test fallback when description is missing
- Test URL formatting in card

**GenericUrlService**
- Test card generation with HTML title
- Test fallback to "Untitled Page" when title missing
- Test description extraction from first text block
- Test truncation of long descriptions
- Test handling of URLs without scrapable content

### Integration Tests

**EmbedManager**
- Test routing to correct service based on URL
- Test end-to-end YouTube embed generation
- Test end-to-end Vimeo embed generation
- Test end-to-end GitHub Gist embed generation
- Test end-to-end OpenGraph card generation
- Test end-to-end generic fallback generation
- Test caching integration across services

**Blade Component**
- Test component rendering with YouTube URL
- Test component rendering with Vimeo URL
- Test component rendering with GitHub Gist URL
- Test component rendering with OpenGraph URL
- Test component rendering with generic URL
- Test custom aspect ratio attribute
- Test cache attribute override

**Validation Rule**
- Test EmbeddableUrl rule accepts valid URLs
- Test EmbeddableUrl rule rejects invalid URLs
- Test service filtering (only YouTube allowed)
- Test service filtering with multiple allowed services
- Test validation error messages

### Feature Tests

**Complete Workflow Tests**
- Test user pastes YouTube URL and sees video embed
- Test user pastes blog URL with OpenGraph and sees rich card
- Test user pastes generic URL and sees basic card
- Test cached metadata improves subsequent request performance
- Test published views can be customized without breaking functionality

**Configuration Tests**
- Test cache TTL from environment variable
- Test HTTP timeout from environment variable
- Test default aspect ratio from configuration
- Test cache can be disabled via configuration
- Test component alias registration

**Error Handling Tests**
- Test graceful handling of HTTP timeout
- Test graceful handling of 404 error
- Test graceful handling of malformed HTML
- Test graceful handling of missing OpenGraph tags
- Test fallback rendering on metadata fetch failure

### Mocking Requirements

**HTTP Responses**
- Mock Guzzle HTTP client responses for metadata fetching
- Provide fixtures for:
  - Complete OpenGraph HTML page
  - Partial OpenGraph HTML page (missing image/description)
  - Generic HTML page with title only
  - HTML page with no useful metadata
  - 404 response
  - 500 response
  - Timeout response

**Cache Driver**
- Use Laravel's `array` cache driver for tests
- No mocking needed (use real cache during tests)

**External Services**
- Do NOT make real HTTP requests to YouTube, Vimeo, or external URLs
- Mock all HTTP responses using Http::fake()

### Test Organization

```
tests/
├── Unit/
│   ├── UrlParserTest.php
│   ├── MetadataFetcherTest.php
│   ├── CacheManagerTest.php
│   └── Services/
│       ├── YouTubeServiceTest.php
│       ├── VimeoServiceTest.php
│       ├── GitHubGistServiceTest.php
│       ├── OpenGraphServiceTest.php
│       └── GenericUrlServiceTest.php
├── Feature/
│   ├── EmbedManagerTest.php
│   ├── BladeComponentTest.php
│   ├── ValidationRuleTest.php
│   ├── ConfigurationTest.php
│   └── ErrorHandlingTest.php
└── Fixtures/
    └── html/
        ├── opengraph-complete.html
        ├── opengraph-partial.html
        ├── generic-page.html
        └── no-metadata.html
```

### Coverage Goals

- Minimum 90% code coverage
- 100% coverage on critical paths (URL parsing, service detection)
- All public methods must have tests
- All configuration options must be tested
- All error paths must be tested

### Testing Patterns

- Use Pest PHP syntax: `test()` or `it()` functions
- Use dataset providers for testing multiple URL formats
- Use `beforeEach()` for common test setup
- Use `Http::fake()` for all external HTTP requests
- Use fixture files for complex HTML responses
- Test edge cases and boundary conditions
- Follow Arrange-Act-Assert pattern

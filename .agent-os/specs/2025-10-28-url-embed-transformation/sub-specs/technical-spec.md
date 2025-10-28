# Technical Specification

This is the technical specification for the spec detailed in @.agent-os/specs/2025-10-28-url-embed-transformation/spec.md

> Created: 2025-10-28
> Version: 1.0.0

## Technical Requirements

### URL Detection and Parsing

- Parse URLs to identify service type (YouTube, Vimeo, GitHub Gist, generic URL)
- Extract service-specific identifiers (video IDs, gist IDs) using regex patterns
- Validate URLs before processing to prevent malformed input
- Support both HTTP and HTTPS protocols
- Handle URLs with query parameters and fragments

### Metadata Fetching

- Fetch OpenGraph metadata from URLs using HTTP client
- Parse HTML to extract `<meta property="og:*">` tags
- Fallback to standard meta tags if OpenGraph not present
- Scrape HTML `<title>` tag for generic URLs
- Extract first text block content for description fallback
- Handle HTTP errors gracefully (404, 500, timeouts)
- Set reasonable timeout for HTTP requests (5 seconds)

### Caching Strategy

- Cache fetched metadata in Laravel cache system
- Use URL as cache key with namespace prefix
- Default TTL: 24 hours (86400 seconds)
- Environment variable: `EMBEDDABLE_LINKS_CACHE_TTL`
- Cache driver: Use application's default cache driver
- Cache tags: Not required for v1 (use simple key-based cache)

### Embed Rendering

- Service-specific embed templates for YouTube, Vimeo, GitHub Gist
- OpenGraph card template with Flux UI card component
- Generic fallback card template with scraped content
- Responsive aspect ratio containers (16:9 default, configurable)
- Lazy loading for iframe embeds
- Support for custom aspect ratios via component attribute

### Component API

- Blade component: `<x-embeddable-links::embed url="..." />`
- Attributes:
  - `url` (required): The URL to embed
  - `aspect-ratio` (optional): Custom aspect ratio (default: 16/9)
  - `cache` (optional): Override cache behavior (default: true)
- Component aliasing: Allow `<x-embed>` shorthand via configuration

### Validation

- Custom validation rule: `EmbeddableUrl`
- Validates URL format using Laravel's `url` rule
- Optional: Restrict to specific services
- Usage: `'url' => ['required', new EmbeddableUrl(['youtube', 'vimeo'])]`

## Approach Options

### Option A: Monolithic Service Class

Create a single `EmbedService` class that handles all URL parsing, metadata fetching, and rendering logic.

**Pros:**
- Simple to understand and maintain
- All logic in one place
- Easy to debug

**Cons:**
- Violates single responsibility principle
- Difficult to test individual components
- Hard to extend with new services
- Tight coupling between concerns

### Option B: Service-Oriented Architecture (Selected)

Create separate service classes for each concern: URL parsing, metadata fetching, rendering, with a facade/manager to coordinate.

**Pros:**
- Single responsibility for each class
- Easy to test components in isolation
- Simple to add new embed services
- Follows Laravel conventions (Manager pattern)
- Loose coupling enables flexibility

**Cons:**
- More files to maintain
- Slightly more complex architecture
- Need to understand service registration

**Rationale:** Service-oriented architecture aligns with Laravel best practices and Kibble's philosophy of clean, maintainable code. The manager pattern is familiar to Laravel developers and makes the package extensible.

### Architecture Design

```
EmbedManager (Facade entry point)
├── UrlParser (detects service type)
├── MetadataFetcher (retrieves OpenGraph/HTML data)
├── CacheManager (handles metadata caching)
└── Services/
    ├── AbstractEmbedService (base class)
    ├── YouTubeService
    ├── VimeoService
    ├── GitHubGistService
    ├── OpenGraphService
    └── GenericUrlService (fallback)
```

## External Dependencies

### Symfony DomCrawler

**Package:** `symfony/dom-crawler`
**Version:** `^7.0`
**Purpose:** Parse HTML to extract metadata, OpenGraph tags, and text content

**Justification:** Robust, well-tested HTML parsing library that integrates seamlessly with Laravel. Provides CSS selector API for easy element extraction. Industry standard for web scraping in PHP ecosystem.

### Symfony CSS Selector

**Package:** `symfony/css-selector`
**Version:** `^7.0`
**Purpose:** Enable CSS selector syntax in DomCrawler

**Justification:** Required companion to DomCrawler for CSS-based element selection. Lightweight and maintained by Symfony core team.

### Guzzle HTTP Client

**Package:** `guzzlehttp/guzzle`
**Already Included:** Yes (Laravel dependency)
**Purpose:** Fetch URL content for metadata extraction

**Justification:** Already included in Laravel, no additional dependency needed. Use via Laravel's `Http` facade for consistency.

## Configuration Options

### Config File: `config/embeddable-links.php`

```php
return [
    // Cache duration in seconds
    'cache_ttl' => env('EMBEDDABLE_LINKS_CACHE_TTL', 86400),

    // HTTP timeout for fetching metadata
    'http_timeout' => env('EMBEDDABLE_LINKS_HTTP_TIMEOUT', 5),

    // Default aspect ratio for embeds
    'aspect_ratio' => env('EMBEDDABLE_LINKS_ASPECT_RATIO', '16/9'),

    // Enable/disable caching
    'cache_enabled' => env('EMBEDDABLE_LINKS_CACHE_ENABLED', true),

    // Supported services (for validation)
    'services' => [
        'youtube',
        'vimeo',
        'github-gist',
    ],

    // Component alias
    'component_alias' => 'embed',
];
```

## View Structure

```
resources/views/
└── components/
    ├── embed.blade.php (main component wrapper)
    └── embeds/
        ├── youtube.blade.php
        ├── vimeo.blade.php
        ├── github-gist.blade.php
        ├── opengraph.blade.php
        └── generic.blade.php
```

## Service Provider Registration

- Register `EmbedManager` as singleton
- Bind individual services to container
- Publish config file
- Publish views with tag `embeddable-links-views`
- Register Blade component
- Register component alias if configured

## Error Handling

- Log failed HTTP requests but don't throw exceptions
- Return generic fallback card on metadata fetch failure
- Validate URLs before attempting to fetch
- Set reasonable HTTP timeouts to prevent hanging
- Provide user-friendly error messages in views

## Performance Considerations

- Cache all fetched metadata by default
- Lazy load iframe embeds with `loading="lazy"`
- Use lightweight HTTP requests (HEAD before GET when possible)
- Consider rate limiting for external API calls in future version
- Minimize DOM parsing operations with efficient selectors

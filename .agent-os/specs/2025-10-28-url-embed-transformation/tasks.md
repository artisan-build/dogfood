# Spec Tasks

These are the tasks to be completed for the spec detailed in @.agent-os/specs/2025-10-28-url-embed-transformation/spec.md

> Created: 2025-10-28
> Status: Ready for Implementation

## Tasks

- [ ] 1. Core Infrastructure Setup
  - [ ] 1.1 Install external dependencies (symfony/dom-crawler, symfony/css-selector)
  - [ ] 1.2 Write tests for UrlParser service
  - [ ] 1.3 Implement UrlParser service with YouTube, Vimeo, GitHub Gist, and generic URL detection
  - [ ] 1.4 Write tests for CacheManager service
  - [ ] 1.5 Implement CacheManager service with configurable TTL
  - [ ] 1.6 Create configuration file with all options (cache TTL, HTTP timeout, aspect ratio, services)
  - [ ] 1.7 Update service provider to merge and publish configuration
  - [ ] 1.8 Verify all tests pass

- [ ] 2. Metadata Fetching and Service Foundation
  - [ ] 2.1 Write tests for MetadataFetcher service
  - [ ] 2.2 Implement MetadataFetcher with OpenGraph extraction and HTML scraping
  - [ ] 2.3 Create AbstractEmbedService base class
  - [ ] 2.4 Write tests for EmbedManager
  - [ ] 2.5 Implement EmbedManager with service routing logic
  - [ ] 2.6 Register EmbedManager and dependencies in service provider
  - [ ] 2.7 Create HTML fixtures for tests (opengraph-complete, opengraph-partial, generic-page, no-metadata)
  - [ ] 2.8 Verify all tests pass

- [ ] 3. Video and Code Embed Services
  - [ ] 3.1 Write tests for YouTubeService
  - [ ] 3.2 Implement YouTubeService with video ID extraction and embed generation
  - [ ] 3.3 Write tests for VimeoService
  - [ ] 3.4 Implement VimeoService with video ID extraction and embed generation
  - [ ] 3.5 Write tests for GitHubGistService
  - [ ] 3.6 Implement GitHubGistService with gist ID extraction and script tag generation
  - [ ] 3.7 Create view templates for YouTube, Vimeo, and GitHub Gist embeds
  - [ ] 3.8 Verify all tests pass

- [ ] 4. OpenGraph and Generic Fallback Services
  - [ ] 4.1 Write tests for OpenGraphService
  - [ ] 4.2 Implement OpenGraphService with card data preparation
  - [ ] 4.3 Write tests for GenericUrlService
  - [ ] 4.4 Implement GenericUrlService with HTML scraping fallback
  - [ ] 4.5 Create opengraph.blade.php view using Flux card component
  - [ ] 4.6 Create generic.blade.php view using Flux card component
  - [ ] 4.7 Ensure cards have consistent sizing with video embeds (grid/stack compatible)
  - [ ] 4.8 Verify all tests pass

- [ ] 5. Blade Component and Validation
  - [ ] 5.1 Write tests for Blade component rendering
  - [ ] 5.2 Create embed.blade.php main component wrapper
  - [ ] 5.3 Implement component logic with url, aspect-ratio, and cache attributes
  - [ ] 5.4 Register Blade component in service provider
  - [ ] 5.5 Implement component alias configuration
  - [ ] 5.6 Write tests for EmbeddableUrl validation rule
  - [ ] 5.7 Implement EmbeddableUrl validation rule with optional service filtering
  - [ ] 5.8 Verify all tests pass

- [ ] 6. Integration Testing, Demo Page, and Documentation
  - [ ] 6.1 Write feature tests for complete workflows (all URL types)
  - [ ] 6.2 Write configuration tests (environment variables, cache settings)
  - [ ] 6.3 Write error handling tests (HTTP failures, timeouts, malformed HTML)
  - [ ] 6.4 Create demo page route and view showing all embed types with no-cache option
  - [ ] 6.5 Add no-cache attribute support to Blade component
  - [ ] 6.6 Verify all view templates are publishable
  - [ ] 6.7 Update README.md with installation, configuration, and usage examples
  - [ ] 6.8 Add code examples for each embed type to README
  - [ ] 6.9 Run composer ready and fix any issues
  - [ ] 6.10 Verify all tests pass with minimum 90% coverage

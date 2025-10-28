# Spec Requirements Document

> Spec: URL Embed Transformation
> Created: 2025-10-28
> Status: Planning

## Overview

Implement a Laravel package that intelligently transforms URLs into rich embedded content, supporting native embeds for video and code services, OpenGraph-based link cards for pages with metadata, and graceful fallback rendering for generic URLs.

## User Stories

### Content Creator

As a content creator, I want to paste URLs into my Laravel application and have them automatically render as appropriate embeds or rich link previews, so that my content is more engaging without manual HTML coding.

When I paste a YouTube URL, it should render as a playable video embed. When I paste a blog post URL, it should render as an attractive card showing the title, image, and description. When I paste any generic URL, it should still look presentable with scraped page information.

### Developer

As a developer integrating this package, I want a simple Blade component API that handles all the complexity of URL detection, metadata fetching, and rendering, so that I can focus on my application logic rather than embed handling.

I should be able to use `<x-embed url="..." />` and have it work correctly for any URL, with sensible defaults and the ability to customize templates if needed.

### Site Administrator

As a site administrator, I want to control caching behavior and customize the appearance of embeds to match my brand, so that performance is optimized and the user experience is consistent.

I should be able to configure cache duration via environment variables and publish views to customize embed templates without modifying the package code.

## Spec Scope

1. **Video Service Embeds** - Native embedded players for YouTube and Vimeo URLs with responsive aspect ratios
2. **Code Service Embeds** - Native embedded viewers for GitHub Gists and similar code-sharing services
3. **OpenGraph Link Cards** - Rich preview cards for URLs with OpenGraph metadata showing title, image, description, and URL
4. **Generic URL Fallback** - Scraped content cards for URLs without OpenGraph tags using HTML title and first text block
5. **Blade Component API** - Simple `<x-embed url="..." />` component with customizable aspect ratios, styling, and cache control
6. **Metadata Caching** - Configurable caching of fetched OpenGraph and scraped metadata with environment variable control and per-component cache bypass option
7. **URL Validation** - Laravel validation rule for embeddable URLs with optional service filtering
8. **Publishable Templates** - All views publishable for customization, with Flux UI card components as default wrapper
9. **Demo Page** - Development demo page showing all embed types with cache bypassing for testing

## Out of Scope

- Social network embeds (Twitter/X, Facebook, Instagram) - low priority for initial release
- Real-time metadata updates - cached data persists for configured duration
- JavaScript-rendered content scraping - only server-side HTML parsing
- Video download or conversion - only embedding via service APIs
- Custom embed service creation API - fixed set of supported services in v1
- Admin UI for managing embeds - configuration file and environment variables only

## Expected Deliverable

1. Package successfully transforms YouTube, Vimeo, and GitHub Gist URLs into appropriate native embeds with responsive sizing
2. URLs with OpenGraph metadata render as attractive link cards displaying title, image, description, and formatted URL
3. Generic URLs without OpenGraph fallback gracefully to cards with scraped HTML title and text excerpt
4. `composer ready` passes all tests, PHPStan level 5, and Pint formatting checks
5. Published templates can be customized without breaking functionality
6. Metadata caching reduces redundant HTTP requests with configurable TTL

## Spec Documentation

- Tasks: @.agent-os/specs/2025-10-28-url-embed-transformation/tasks.md
- Technical Specification: @.agent-os/specs/2025-10-28-url-embed-transformation/sub-specs/technical-spec.md
- Tests Specification: @.agent-os/specs/2025-10-28-url-embed-transformation/sub-specs/tests.md

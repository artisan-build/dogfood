<p align="center"><img src="https://github.com/artisan-build/embeddable-links/raw/HEAD/art/embeddable-links.png" width="75%" alt="Artisan Build Package Embeddable Links Logo"></p>

# Embeddable Links

Transform URLs into rich embedded content for Laravel applications. Automatically detects and renders embeds for YouTube, Vimeo, GitHub Gists, and generates beautiful link preview cards using OpenGraph metadata.

> [!WARNING]
> This package is currently under active development, and we have not yet released a major version. Once a 0.* version
> has been tagged, we strongly recommend locking your application to a specific working version because we might make
> breaking changes even in patch releases until we've tagged 1.0.

## Features

- 🎥 **Video Embeds**: Native players for YouTube and Vimeo with responsive aspect ratios
- 💻 **Code Embeds**: GitHub Gist embedding with syntax highlighting
- 🔗 **OpenGraph Cards**: Beautiful link previews with title, image, and description
- 🌐 **Generic Fallback**: Smart metadata extraction for any URL
- ⚡ **Caching**: Configurable metadata caching to minimize HTTP requests
- 🎨 **Flux UI**: Built with Flux UI Pro components (customizable)
- ✅ **Validation**: Laravel validation rule for embeddable URLs

## Installation

```bash
composer require artisan-build/embeddable-links
```

## Configuration

Publish the configuration file (optional):

```bash
php artisan vendor:publish --tag=embeddable-links-config
```

### Environment Variables

```env
EMBEDDABLE_LINKS_CACHE_ENABLED=true
EMBEDDABLE_LINKS_CACHE_TTL=86400  # 24 hours in seconds
EMBEDDABLE_LINKS_HTTP_TIMEOUT=5   # HTTP request timeout in seconds
EMBEDDABLE_LINKS_ASPECT_RATIO=16/9
```

## Usage

### Basic Blade Component

```blade
<x-embeddable-links-embed url="https://www.youtube.com/watch?v=dQw4w9WgXcQ" />
```

### Custom Aspect Ratio

```blade
<x-embeddable-links-embed
    url="https://vimeo.com/123456789"
    aspect-ratio="4/3"
/>
```

### Disable Caching

```blade
<x-embeddable-links-embed
    url="https://example.com/article"
    :cache="false"
/>
```

### Supported Services

**YouTube:**
```blade
<x-embeddable-links-embed url="https://www.youtube.com/watch?v=VIDEO_ID" />
<x-embeddable-links-embed url="https://youtu.be/VIDEO_ID" />
```

**Vimeo:**
```blade
<x-embeddable-links-embed url="https://vimeo.com/VIDEO_ID" />
```

**GitHub Gist:**
```blade
<x-embeddable-links-embed url="https://gist.github.com/username/GIST_ID" />
```

**Any URL with OpenGraph:**
```blade
<x-embeddable-links-embed url="https://laravel.com/docs" />
```

### URL Validation

Use the `EmbeddableUrl` validation rule:

```php
use ArtisanBuild\EmbeddableLinks\Rules\EmbeddableUrl;

$request->validate([
    'url' => ['required', 'url', new EmbeddableUrl],
]);
```

Restrict to specific services:

```php
$request->validate([
    'url' => ['required', 'url', new EmbeddableUrl(['youtube', 'vimeo'])],
]);
```

### Programmatic Usage

```php
use ArtisanBuild\EmbeddableLinks\Services\EmbedManager;

$manager = app(EmbedManager::class);
$html = $manager->embed('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
```

## Customization

### Publish Views

```bash
php artisan vendor:publish --tag=embeddable-links-views
```

Views will be published to `resources/views/vendor/embeddable-links/embeds/`:
- `youtube.blade.php`
- `vimeo.blade.php`
- `github-gist.blade.php`
- `opengraph.blade.php`
- `generic.blade.php`

### Demo Page

Visit `/embeddable-links-demo` to see all embed types in action (development only).

## Testing

```bash
composer test
composer test-coverage
```

## Code Quality

```bash
composer lint       # Fix code style
composer stan       # Run static analysis
composer ready      # Run all checks
```

## How It Works

1. **URL Detection**: Parses URLs to identify service type (YouTube, Vimeo, Gist, or generic)
2. **Metadata Fetching**: For generic URLs, fetches OpenGraph and HTML metadata
3. **Caching**: Stores metadata in Laravel cache (default: 24 hours)
4. **Rendering**: Uses service-specific Blade templates with Flux UI components

## Requirements

- PHP 8.3+
- Laravel 11.0+
- Flux UI Pro (for default card styles)

## Memberware

This package is part of our internal toolkit and is optimized for our own purposes. We do not accept issues or PRs
in this repository.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information. 


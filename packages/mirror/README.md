<p align="center"><img src="https://github.com/artisan-build/mirror/raw/HEAD/art/mirror.png" width="75%" alt="Artisan Build Package Mirror Logo"></p>

# Mirror

A more elegant way to interact with PHPs Reflection API

> [!WARNING]  
> This package is currently under active development, and we have not yet released a major version. Once a 0.* version
> has been tagged, we strongly recommend locking your application to a specific working version because we might make
> breaking changes even in patch releases until we've tagged 1.0.

We're heavy users of Reflection, and we're finding that it can become a bit verbose and repetitive. So Mirror is a tiny package that simply hides away some of that verbosity and lets us get to what we need quickly.

**Important -** We are not trying to abstract away all of Reflection. Our goal is just to create a nice API around the things that we use most when reaching for Reflection.

## Installation

`composer require artisan-build/mirror`

## Configuration

## Usage

```php
use ArtisanBuild\Mirror\Facades\Mirror;

/** @var ReflectionService $mirror */
$mirror = Mirror::reflect(\ArtisanBuild\Mirror\TestClasses\ExtendsAbstractClass::class);

/** @var ReflectionClass $reflection */
$reflection = $mirror->reflection;

Mirror::reflect(\ArtisanBuild\Mirror\TestClasses\ExtendsNothing::class)
    ->property('public_string');
```

## Memberware

This package is part of our internal toolkit and is optimized for our own purposes. We do not accept issues or PRs in this repository. 


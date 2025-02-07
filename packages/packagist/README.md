# Packagist

Laravel wrapper for the Packagist API

> [!WARNING]  
> This package is currently under active development, and we have not yet released a major version. Once a 0.* version
> has been tagged, we strongly recommend locking your application to a specific working version because we might make
> breaking changes even in patch releases until we've tagged 1.0.

## Installation

`composer require artisan-build/packagist`

## Configuration

Log into your account at Packagist and copy the following values into your .env file:

```dotenv
PACKAGIST_USERNAME=
PACKAGIST_MAIN_TOKEN=
PACKAGIST_SAFE_TOKEN=
```

## Usage

This package uses [Saloon](https://docs.saloon.dev) to wrap almost all the API endpoints offered by Packagist.

We do not have any tests set up yet for this package, and we are considering using the Laravel Saloon package to refactor
this to take advantage of a facade to facilitate testing within apps that consume this package.

Even if we do this, there is little chance that we will significantly change the existing implementation.

### The Connector

There is one connector that is used for every request. It is called `PackagistConnector`.

```php
use ArtisanBuild\Packagist\Connectors\PackagistConnector;

$connector = new PackagistConnector();
```

### The Requests

See the [Packagist API Docs](https://packagist.org/apidoc) for details on the constructor arguments.

```php
// List Packages
use ArtisanBuild\Packagist\Requests\ListPackagesRequest;
$request = new ListPackagesRequest(vendor: null, type: null, fields: [])
$connector->send($request)
```

```php
// List Popular Packages
use ArtisanBuild\Packagist\Requests\ListPopularPackagesRequest;
$request = new ListPopularPackagesRequest(page: 1, per_page: 100)
$connector->send($request)
```

```php
// Search Packages
use ArtisanBuild\Packagist\Requests\SearchRequest;
$request = new SearchRequest(q: 'Laravel', page: 1, per_page: 25, tags: null, type: null);
$connector->send($request)
```

```php
// Get Package Info
use ArtisanBuild\Packagist\Requests\PackageInfoRequest;
$request = new PackageInfoRequest(vendor: 'artisan-build', package: 'packagist');
$connector->send($request)
```

```php
// Get Package Stats
use ArtisanBuild\Packagist\Requests\PackageStatsRequest;
$request = new PackageStatsRequest(vendor: 'artisan-build', package: 'packagist');
$connector->send($request)
```

```php
use ArtisanBuild\Packagist\Requests\PackageMetadataRequest;
$request = new PackageMetadataRequest(vendor: 'artisan-build', package: 'packagist', dev: false);
$connector->send($request)
```

A quick note about the metadata request above... The `dev` argument does not seem to work. I'm not entirely sure what
this is actually supposed to return, but it's in the docs. However, when I set it to true and append `-dev` to the
package name as described in the Packagist docs, I get an error. It's not something we need ourselves, so I'm not going
to worry too much about it, but if anyone knows how to fix it and can explain what it's useful for, we'd love to hear 
from you.

```php
// Create Package
use ArtisanBuild\Packagist\Requests\CreatePackageRequest;

$request = new CreatePackageRequest(
    repository: 'https://github.com/artisan-build/packagist',
    username: null,
    token: null, // main token
);
```

```php
// Edit a Package URL
use ArtisanBuild\Packagist\Requests\CreatePackageRequest;

$request = new EditPackageRequest(
    vendor: 'artisan-build',
    package: 'packagist'
    repository: 'https://github.com/artisan-build/packagist-laravel',
    username: null,
    token: null, // main token
);
```

```php
// Update a package
use ArtisanBuild\Packagist\Requests\UpdatePackageRequest;

$request = new UpdatePackageRequest(
    repository: 'https://github.com/artisan-build/packagist',
    username: null,
    token: null, // safe token if set, main token if not
);
```










## Memberware

This package is part of our internal toolkit and is optimized for our own purposes. We do not accept issues or PRs
in this repository. 


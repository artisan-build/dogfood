# Flux Themes

This package sets up a Laravel application to use [FluxUI](https://fluxui.dev). It has one command that you can run when setting up a new site that will let you set or choose a color scheme from Flux's themes and it will make all of the required edits to the `tailwind.config.js` package and `app.css` package to run Flux and use your chosen color scheme.

Some of our applications are "white label" type SaaS applications that we want our users to be able to theme to match
their brands. This package provides the functionality to make that happen.

We have included all of [Flux's color schemes](https://fluxui.dev/themes) with their chosen variety of gray. 

> [!WARNING]  
> This package is currently under active development, and we have not yet released a major version. Once a 0.* version
> has been tagged, we strongly recommend locking your application to a specific working version because we might make
> breaking changes even in patch releases until we've tagged 1.0.

> [!WARNING]  
> This package requires FluxUI, a paid commercial package. In order to install this package, you have to have a license
> for FluxUI and have composer set to authenticate with Flux's server. See [FluxUI](https://fluxui.dev) for more
> information and to purchase a license.

## Installation

`composer require artisan-build/flux-themes`

## Configuration

There are currently no configuration options for this package.

## Usage

`php artisan flux-theme:set {color?}`

Run this command to set the color scheme for your site. This will handle the entire FluxUI setup in both the `app.css`
and `tailwind.config.js` file. If you don't pass a color, you'll be prompted to choose your color. 

One thing to note is that an additional entry is added to the `content` block of the `tailwind.config.css` file that
adds a wildcard path to all of our packages. We do this for our own purposes. You can safely leave this line in place
(and you'll want to if you use any of our other Flux-related packages or the more advanced features of this one) or
you may safely remove it if you're just using this command to set up your FluxUI color scheme.

For more advanced ways to use this package and to learn how we use it in our "environment per tenant" SaaS applications,
join the [Artisan Build Community](https://artisan.community).

## Memberware

This package is part of our internal toolkit and is optimized for our own purposes. We do not accept issues or PRs
in this repository. 


# October 2025 Roadmap

## New Packages

**RunOnce -** A very simple package that provides a `php artisan run-once` command that runs every class in the `App\Actions\RunOnce` directory. The purpose of this package is to provide a command that can be added to a deploy script that will perform one-time actions in production. This will be one of those **caveat emptor** packages. We're building it for our own purposes, and our use case requires that these actions are invokable classes with idempotent business logic. So in truth, they'll be run on every single deployment until they're deleted, but only the initial run will have any side effects. This package will be added to our starter kit.

## Package Removals

**HallwayCore -** This package will be removed from kibble and archived in GitHub. It will serve as guidance for when we add chat to the Hallway application, but we won't actually use the code it contains because the architecture isn't right for the new use case.

**HallwayFlux -** Same as HallwayCore

## Universal Changes

All README.md files should be fully built out so that they conform with the usual open-source standards.

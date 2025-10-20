# Spec Tasks

These are the tasks to be completed for the spec detailed in @.agent-os/specs/2025-10-20-rename-forge-sdk/spec.md

> Created: 2025-10-20
> Status: Ready for Implementation

## Tasks

- [x] 1. Rename package directory and update root composer.json
  - [x] 1.1 Use `git mv` to rename `packages/forge-sdk` to `packages/forge-client`
  - [x] 1.2 Update root `composer.json` path repository from `packages/forge-sdk` to `packages/forge-client`
  - [x] 1.3 Update root `composer.json` require section from `artisan-build/forge-sdk` to `artisan-build/forge-client`
  - [x] 1.4 Run `composer install` to verify autoloading works
  - [x] 1.5 Run `composer dump-autoload` to regenerate autoloader

- [x] 2. Update package composer.json and service provider
  - [x] 2.1 Update package name from `artisan-build/forge-sdk` to `artisan-build/forge-client` in `packages/forge-client/composer.json`
  - [x] 2.2 Update description to reference "client" instead of "SDK"
  - [x] 2.3 Update autoload PSR-4 namespace from `ArtisanBuild\\ForgeSdk\\` to `ArtisanBuild\\ForgeClient\\`
  - [x] 2.4 Update test autoload namespace from `ArtisanBuild\\ForgeSdk\\Tests\\` to `ArtisanBuild\\ForgeClient\\Tests\\`
  - [x] 2.5 Update service provider class reference in `extra.laravel.providers`
  - [x] 2.6 Update facade class reference in `extra.laravel.aliases`

- [x] 3. Rename and update service provider class
  - [x] 3.1 Rename `src/Providers/ForgeSdkServiceProvider.php` to `src/Providers/ForgeClientServiceProvider.php`
  - [x] 3.2 Update class name from `ForgeSdkServiceProvider` to `ForgeClientServiceProvider`
  - [x] 3.3 Update namespace in file to `ArtisanBuild\ForgeClient\Providers`
  - [x] 3.4 Update config publishing key from `forge-sdk` to `forge-client`
  - [x] 3.5 Update any string references to the package name

- [x] 4. Rename configuration file
  - [x] 4.1 Rename `config/forge-sdk.php` to `config/forge-client.php`
  - [x] 4.2 Update service provider `publishes()` method to reference new config filename
  - [x] 4.3 Search for any `config('forge-sdk.*')` references and update to `config('forge-client.*')`

- [x] 5. Update all PHP file namespaces in src/ directory
  - [x] 5.1 Update namespace declarations from `ArtisanBuild\ForgeSdk\` to `ArtisanBuild\ForgeClient\` in all src PHP files
  - [x] 5.2 Update all `use ArtisanBuild\ForgeSdk\` statements to `use ArtisanBuild\ForgeClient\`
  - [x] 5.3 Update class references in DocBlocks (@param, @return, @var tags)
  - [x] 5.4 Update string references to namespace (e.g., in service provider, facade)
  - [x] 5.5 Verify main `ForgeSdk.php` class renamed to `ForgeClient.php` with updated class name

- [x] 6. Update all PHP file namespaces in tests/ directory
  - [x] 6.1 Update namespace declarations from `ArtisanBuild\ForgeSdk\Tests\` to `ArtisanBuild\ForgeClient\Tests\` in all test files
  - [x] 6.2 Update all `use ArtisanBuild\ForgeSdk\` statements to `use ArtisanBuild\ForgeClient\`
  - [x] 6.3 Update test class references and assertions
  - [x] 6.4 Update service provider test class name from `ForgeSdkServiceProviderTest` to `ForgeClientServiceProviderTest`

- [x] 7. Update README and documentation
  - [x] 7.1 Update package name references in README.md from `forge-sdk` to `forge-client`
  - [x] 7.2 Update namespace examples in code snippets
  - [x] 7.3 Update composer require command to `composer require artisan-build/forge-client`
  - [x] 7.4 Update any facade usage examples

- [x] 8. Run complete test suite and verify
  - [x] 8.1 Run `composer install` from root to ensure dependencies resolve
  - [x] 8.2 Run `composer dump-autoload` to regenerate autoloader
  - [x] 8.3 Run `composer test` to run full test suite
  - [x] 8.4 Run `composer lint` to verify code style
  - [x] 8.5 Run `composer stan` to verify static analysis
  - [x] 8.6 Run `composer ready` to run complete quality suite
  - [x] 8.7 Verify all tests pass and no errors reported

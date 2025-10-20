# Spec Tasks

These are the tasks to be completed for the spec detailed in @.agent-os/specs/2025-10-20-rename-forge-sdk/spec.md

> Created: 2025-10-20
> Status: Ready for Implementation

## Tasks

- [ ] 1. Rename package directory and update root composer.json
  - [ ] 1.1 Use `git mv` to rename `packages/forge-sdk` to `packages/forge-client`
  - [ ] 1.2 Update root `composer.json` path repository from `packages/forge-sdk` to `packages/forge-client`
  - [ ] 1.3 Update root `composer.json` require section from `artisan-build/forge-sdk` to `artisan-build/forge-client`
  - [ ] 1.4 Run `composer install` to verify autoloading works
  - [ ] 1.5 Run `composer dump-autoload` to regenerate autoloader

- [ ] 2. Update package composer.json and service provider
  - [ ] 2.1 Update package name from `artisan-build/forge-sdk` to `artisan-build/forge-client` in `packages/forge-client/composer.json`
  - [ ] 2.2 Update description to reference "client" instead of "SDK"
  - [ ] 2.3 Update autoload PSR-4 namespace from `ArtisanBuild\\ForgeSdk\\` to `ArtisanBuild\\ForgeClient\\`
  - [ ] 2.4 Update test autoload namespace from `ArtisanBuild\\ForgeSdk\\Tests\\` to `ArtisanBuild\\ForgeClient\\Tests\\`
  - [ ] 2.5 Update service provider class reference in `extra.laravel.providers`
  - [ ] 2.6 Update facade class reference in `extra.laravel.aliases`

- [ ] 3. Rename and update service provider class
  - [ ] 3.1 Rename `src/Providers/ForgeSdkServiceProvider.php` to `src/Providers/ForgeClientServiceProvider.php`
  - [ ] 3.2 Update class name from `ForgeSdkServiceProvider` to `ForgeClientServiceProvider`
  - [ ] 3.3 Update namespace in file to `ArtisanBuild\ForgeClient\Providers`
  - [ ] 3.4 Update config publishing key from `forge-sdk` to `forge-client`
  - [ ] 3.5 Update any string references to the package name

- [ ] 4. Rename configuration file
  - [ ] 4.1 Rename `config/forge-sdk.php` to `config/forge-client.php`
  - [ ] 4.2 Update service provider `publishes()` method to reference new config filename
  - [ ] 4.3 Search for any `config('forge-sdk.*')` references and update to `config('forge-client.*')`

- [ ] 5. Update all PHP file namespaces in src/ directory
  - [ ] 5.1 Update namespace declarations from `ArtisanBuild\ForgeSdk\` to `ArtisanBuild\ForgeClient\` in all src PHP files
  - [ ] 5.2 Update all `use ArtisanBuild\ForgeSdk\` statements to `use ArtisanBuild\ForgeClient\`
  - [ ] 5.3 Update class references in DocBlocks (@param, @return, @var tags)
  - [ ] 5.4 Update string references to namespace (e.g., in service provider, facade)
  - [ ] 5.5 Verify main `ForgeSdk.php` class renamed to `ForgeClient.php` with updated class name

- [ ] 6. Update all PHP file namespaces in tests/ directory
  - [ ] 6.1 Update namespace declarations from `ArtisanBuild\ForgeSdk\Tests\` to `ArtisanBuild\ForgeClient\Tests\` in all test files
  - [ ] 6.2 Update all `use ArtisanBuild\ForgeSdk\` statements to `use ArtisanBuild\ForgeClient\`
  - [ ] 6.3 Update test class references and assertions
  - [ ] 6.4 Update service provider test class name from `ForgeSdkServiceProviderTest` to `ForgeClientServiceProviderTest`

- [ ] 7. Update README and documentation
  - [ ] 7.1 Update package name references in README.md from `forge-sdk` to `forge-client`
  - [ ] 7.2 Update namespace examples in code snippets
  - [ ] 7.3 Update composer require command to `composer require artisan-build/forge-client`
  - [ ] 7.4 Update any facade usage examples

- [ ] 8. Run complete test suite and verify
  - [ ] 8.1 Run `composer install` from root to ensure dependencies resolve
  - [ ] 8.2 Run `composer dump-autoload` to regenerate autoloader
  - [ ] 8.3 Run `composer test` to run full test suite
  - [ ] 8.4 Run `composer lint` to verify code style
  - [ ] 8.5 Run `composer stan` to verify static analysis
  - [ ] 8.6 Run `composer ready` to run complete quality suite
  - [ ] 8.7 Verify all tests pass and no errors reported

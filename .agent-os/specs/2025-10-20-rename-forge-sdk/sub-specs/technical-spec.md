# Technical Specification

This is the technical specification for the spec detailed in @.agent-os/specs/2025-10-20-rename-forge-sdk/spec.md

> Created: 2025-10-20
> Version: 1.0.0

## Technical Requirements

### Directory Structure Changes

- Rename `/packages/forge-sdk/` to `/packages/forge-client/`
- Maintain all subdirectory structure within the package
- Preserve all file contents initially, then perform namespace replacements

### Namespace Transformations

- **Primary Namespace**: `ArtisanBuild\ForgeSdk\` → `ArtisanBuild\ForgeClient\`
- **Test Namespace**: `ArtisanBuild\ForgeSdk\Tests\` → `ArtisanBuild\ForgeClient\Tests\`
- **All PHP Files**: Update namespace declarations in all `.php` files
- **Use Statements**: Update all `use` statements importing ForgeSdk classes
- **DocBlocks**: Update any @param, @return, @var tags referencing ForgeSdk classes
- **String References**: Update any string references to the namespace (e.g., in service provider registration)

### Composer Configuration Changes

**Package composer.json** (`packages/forge-client/composer.json`):
- `name`: `artisan-build/forge-sdk` → `artisan-build/forge-client`
- `description`: Update to mention "client" instead of "SDK"
- `autoload.psr-4`: `ArtisanBuild\\ForgeSdk\\` → `ArtisanBuild\\ForgeClient\\`
- `extra.laravel.providers`: Update provider class reference
- `extra.laravel.aliases.Forge`: Update facade class reference

**Root composer.json** (`composer.json`):
- Update path repository from `packages/forge-sdk` to `packages/forge-client`
- Update package requirement from `artisan-build/forge-sdk` to `artisan-build/forge-client`

### Configuration File Changes

- Rename `config/forge-sdk.php` to `config/forge-client.php`
- Update service provider to publish config with new filename
- Update any references to the config key from `forge-sdk` to `forge-client`

### Service Provider Updates

**ForgeSdkServiceProvider.php → ForgeClientServiceProvider.php**:
- Rename class from `ForgeSdkServiceProvider` to `ForgeClientServiceProvider`
- Update namespace
- Update config publishing to use `forge-client` key
- Update command registration if using namespace strings

### Facade Updates

**Forge Facade**:
- Update `getFacadeAccessor()` to return updated binding key if needed
- Ensure facade alias in composer.json points to new namespace

## Approach

**Single Approach - Systematic Rename**:

This refactoring follows a systematic approach:

1. **Git Move**: Use `git mv` to rename the package directory, preserving git history
2. **Namespace Replacement**: Use find/replace across all PHP files in the package
3. **Composer Updates**: Update both package and root composer.json files
4. **Config Rename**: Rename config file and update references
5. **Verification**: Run `composer install` and `composer ready` to verify changes
6. **Test Execution**: Ensure all tests pass with the new namespace

**Rationale**: This approach ensures git history is preserved, all references are updated systematically, and we verify the changes work through automated testing.

## Implementation Details

### Files Requiring Namespace Updates

Based on grep results, approximately 372 files contain `ForgeSdk` references:
- All files in `src/` directory
- All files in `tests/` directory
- `composer.json`
- `README.md` (for usage examples)
- Config file

### Search and Replace Strategy

Use case-sensitive replacements in this order:
1. `ArtisanBuild\\ForgeSdk\\` → `ArtisanBuild\\ForgeClient\\` (namespace declarations)
2. `ArtisanBuild\ForgeSdk\` → `ArtisanBuild\ForgeClient\` (use statements)
3. `ForgeSdk` → `ForgeClient` (class name references)
4. `forge-sdk` → `forge-client` (config keys, package names)
5. `forge_sdk` → `forge_client` (any snake_case references)

### Potential Edge Cases

- **Artisan Commands**: Command signatures should remain unchanged
- **Config Keys**: Users may have published config - document migration path
- **Cached Config**: Clear config cache after changes
- **IDE Helper**: Regenerate IDE helper files after namespace change

## External Dependencies

No new external dependencies required. This is purely a refactoring of existing code.

## Testing Strategy

1. **Unit Tests**: All existing unit tests should pass without modification (except namespace updates in test files)
2. **Feature Tests**: All feature tests should pass
3. **Integration**: Root-level `composer ready` must pass
4. **Manual Verification**: Import package in test context to verify autoloading works

## Rollback Plan

If issues arise:
1. Git history is preserved via `git mv`
2. Can revert the branch and start over
3. Or create a reverse transformation following the same steps

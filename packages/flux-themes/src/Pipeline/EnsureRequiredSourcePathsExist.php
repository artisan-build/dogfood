<?php

namespace ArtisanBuild\FluxThemes\Pipeline;

use ArtisanBuild\FluxThemes\Actions\DetectPackagesWithTemplates;
use ArtisanBuild\FluxThemes\Theme;
use Closure;

class EnsureRequiredSourcePathsExist
{
    public function __construct(private readonly DetectPackagesWithTemplates $packages_with_templates) {}

    public function __invoke(Theme $theme, Closure $next): Theme
    {
        $laravel = [
            "@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';",
            "@source '../../storage/framework/views/*.php';",
            "@source '../../resources/**/*.blade.php';",
            "@source '.../../resources/**/*.js';",
            "@source '../../resources/**/*.vue';",
        ];
        $flux = [
            "@source '../../vendor/livewire/flux/stubs/**/*.blade.php';",
            "@source '../../vendor/livewire/flux-pro/stubs/**/*.blade.php';",
        ];
        $packages = ($this->packages_with_templates)()
            ->map(fn ($package) => "@source '{$package}/resources/views/*.blade.php';")
            ->toArray();

        $required = array_merge($laravel, $flux, $packages);

        // Filter out paths that already exist in the CSS
        $missing_paths = collect($required)->reject(fn ($path): bool => str_contains($theme->css, (string) $path));

        if ($missing_paths->isNotEmpty()) {
            // Convert CSS content to collection of lines
            $lines = collect(explode("\n", $theme->css));

            // Get the index of the last @import by checking all lines
            $last_import_index = $lines
                ->reduce(fn ($carry, $line, $index) => str_contains((string) $line, '@import') ? $index : $carry, -1);

            if ($last_import_index !== -1) {
                // Get all lines up to and including the last @import
                $before = $lines->take($last_import_index + 1);
                // Get all remaining lines
                $after = $lines->skip($last_import_index + 1);

                // Ensure we add a newline after imports if there isn't one
                if ($missing_paths->isNotEmpty() && trim((string) $before->last()) !== '') {
                    $missing_paths->prepend('');
                }

                // Rebuild the CSS content
                $theme->css = $before
                    ->merge($missing_paths)
                    ->when($after->isNotEmpty(), fn ($collection) => $collection->merge([''])->merge($after))
                    ->filter()
                    ->implode("\n");
            } else {
                // If no @import found, add at the beginning
                $theme->css = $missing_paths
                    ->merge([''])  // Add a blank line between imports and content
                    ->merge($lines)
                    ->filter()
                    ->implode("\n");
            }
        }

        return $next($theme);
    }
}

<?php

namespace ArtisanBuild\FluxThemes\Actions;

use ArtisanBuild\FluxThemes\Theme;
use Illuminate\Support\Facades\File;
use RuntimeException;

class WriteToTailwindConfig
{
    public function __invoke(Theme $theme)
    {
        $configPath = $theme->tailwind_config;

        if (! File::exists($configPath)) {
            throw new RuntimeException("Tailwind config file not found at: {$configPath}");
        }

        $configContent = File::get($configPath);

        // Remove existing colors block if it exists
        $configContent = $this->removeColorsBlock($configContent);

        // Ensure the colors object exists
        $configContent = $this->ensureColorsAreSet($configContent);

        // Build and insert the new colors block
        $newColorsBlock = $this->generateColorsBlock($theme);
        $configContent = $this->insertColorsBlock($configContent, $newColorsBlock);
        $configContent = $this->ensurePathsAreSet($configContent);

        File::put($configPath, preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $configContent));
    }

    protected function ensureColorsAreSet(string $configContent): string
    {
        if (! str_contains($configContent, "const colors = require('tailwindcss/colors');")) {
            $configContent = implode("\n", ["const colors = require('tailwindcss/colors');", $configContent]);
        }

        return $configContent;
    }

    protected function removeColorsBlock(string $configContent): string
    {
        $configContent = preg_replace('/accent:\s*{.*?}\s*,/s', '', $configContent);
        $pattern = '/colors:\s*{.*?}\s*,/s';

        return preg_replace($pattern, '', (string) $configContent);
    }

    protected function generateColorsBlock(Theme $theme): string
    {
        $colors = str_replace('__ZINC__', $theme->gray->config(), "\t\t\tcolors: {
                __ZINC__
                accent: {
                    DEFAULT: 'var(--color-accent)',
                    content: 'var(--color-accent-content)',
                    foreground: 'var(--color-accent-foreground)',
                },
            },");

        return $colors;
    }

    protected function insertColorsBlock(string $configContent, string $newColorsBlock): string
    {
        $pattern = '/extend:\s*{/';
        $replacement = "extend: {\n$newColorsBlock";

        return preg_replace($pattern, $replacement, $configContent);
    }

    protected function ensurePathsAreSet(string $configContent): string
    {
        $paths = [
            './vendor/livewire/flux-pro/stubs/**/*.blade.php',
            './vendor/livewire/flux/stubs/**/*.blade.php',
            './vendor/artisan-build/**/resources/**/*.blade.php',
        ];

        foreach ($paths as $path) {
            if (! str_contains($configContent, $path)) {
                $configContent = str_replace("content: [\n", "content: [\n\t\t'$path',\n", $configContent);
            }
        }

        return $configContent;
    }
}

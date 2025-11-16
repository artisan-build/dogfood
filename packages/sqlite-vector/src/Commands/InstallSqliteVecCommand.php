<?php

declare(strict_types=1);

namespace ArtisanBuild\SqliteVector\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class InstallSqliteVecCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sqlite-vec:install
                            {--force : Reinstall even if extension already exists}
                            {--version= : Install specific version (default: latest)}';

    /**
     * The console command description.
     */
    protected $description = 'Download and install the sqlite-vec extension for the current platform';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Installing sqlite-vec extension...');

        // Detect current platform
        $platform = $this->detectPlatform(PHP_OS_FAMILY, php_uname('m'));

        if (! $platform) {
            $this->error('Unsupported platform: '.PHP_OS_FAMILY.' '.php_uname('m'));
            $this->line('Supported platforms:');
            $this->line('  - macOS (Intel/ARM)');
            $this->line('  - Linux (x86_64/ARM64)');
            $this->line('  - Windows (x86_64)');

            return self::FAILURE;
        }

        $this->info("Detected platform: {$platform}");

        // Get storage path
        $storagePath = storage_path('sqlite-vec');

        // Create storage directory if it doesn't exist
        if (! File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        // Check if already installed
        $extensionFilename = $this->getExtensionFilename($platform);
        $extensionPath = $storagePath.'/'.$extensionFilename;

        if (File::exists($extensionPath) && ! $this->option('force')) {
            $this->info('Extension already installed. Use --force to reinstall.');

            return self::SUCCESS;
        }

        // Get version to install
        $version = $this->option('version');

        // Fetch releases from GitHub
        $this->info('Fetching releases from GitHub...');

        try {
            $release = $this->fetchRelease($version);
        } catch (\Exception $e) {
            $this->error('Failed to fetch releases: '.$e->getMessage());
            $this->line('Manual download: https://github.com/asg017/sqlite-vec/releases');

            return self::FAILURE;
        }

        // Find matching asset
        $asset = $this->findAsset($release['assets'], $platform);

        if (! $asset) {
            $this->error("No matching asset found for platform: {$platform}");

            return self::FAILURE;
        }

        // Download asset
        $this->info("Downloading {$asset['name']}...");

        try {
            $content = Http::get($asset['browser_download_url'])->throw()->body();
        } catch (\Exception $e) {
            $this->error('Failed to download: '.$e->getMessage());

            return self::FAILURE;
        }

        // Save to temporary file
        $tempPath = $storagePath.'/'.basename($asset['name']);
        File::put($tempPath, $content);

        // Extract if needed
        $this->info('Extracting...');

        try {
            $this->extractExtension($tempPath, $extensionPath, $platform);
        } catch (\Exception $e) {
            $this->error('Failed to extract: '.$e->getMessage());
            File::delete($tempPath);

            return self::FAILURE;
        }

        // Clean up temp file
        if ($tempPath !== $extensionPath) {
            File::delete($tempPath);
        }

        // Set permissions
        chmod($extensionPath, 0644);

        // Validate installation
        $this->info('Validating installation...');

        if (! File::exists($extensionPath)) {
            $this->error('Extension file not found after installation.');

            return self::FAILURE;
        }

        $this->info('✓ Extension installed successfully!');
        $this->line("Location: {$extensionPath}");

        return self::SUCCESS;
    }

    /**
     * Detect the current platform.
     */
    public function detectPlatform(string $osFamily, string $machine): ?string
    {
        return match ($osFamily) {
            'Darwin' => match ($machine) {
                'x86_64' => 'macos-x86_64',
                'arm64' => 'macos-aarch64',
                default => null,
            },
            'Linux' => match ($machine) {
                'x86_64' => 'linux-x86_64',
                'aarch64', 'arm64' => 'linux-aarch64',
                default => null,
            },
            'Windows' => match ($machine) {
                'AMD64', 'x86_64' => 'windows-x86_64',
                default => null,
            },
            default => null,
        };
    }

    /**
     * Get the extension filename for the platform.
     */
    public function getExtensionFilename(string $platform): string
    {
        if (str_starts_with($platform, 'macos')) {
            return 'vec0.dylib';
        }

        if (str_starts_with($platform, 'linux')) {
            return 'vec0.so';
        }

        if (str_starts_with($platform, 'windows')) {
            return 'vec0.dll';
        }

        return 'vec0'.PHP_SHLIB_SUFFIX;
    }

    /**
     * Fetch release information from GitHub.
     */
    protected function fetchRelease(?string $version): array
    {
        $url = $version
            ? "https://api.github.com/repos/asg017/sqlite-vec/releases/tags/{$version}"
            : 'https://api.github.com/repos/asg017/sqlite-vec/releases/latest';

        $response = Http::get($url)->throw();

        return $response->json();
    }

    /**
     * Find matching asset for platform.
     */
    protected function findAsset(array $assets, string $platform): ?array
    {
        foreach ($assets as $asset) {
            if (str_contains($asset['name'], $platform)) {
                return $asset;
            }
        }

        return null;
    }

    /**
     * Extract extension from archive.
     */
    protected function extractExtension(string $archivePath, string $outputPath, string $platform): void
    {
        $extension = pathinfo($archivePath, PATHINFO_EXTENSION);

        if ($extension === 'gz' || str_contains($archivePath, '.tar.gz')) {
            // Extract tar.gz
            $phar = new \PharData($archivePath);
            $phar->extractTo(dirname($outputPath), null, true);

            // Find the extracted extension file
            $extractedFile = dirname($outputPath).'/'.$this->getExtensionFilename($platform);

            if (File::exists($extractedFile) && $extractedFile !== $outputPath) {
                File::move($extractedFile, $outputPath);
            }
        } elseif ($extension === 'zip') {
            // Extract zip
            $zip = new \ZipArchive;

            if ($zip->open($archivePath) === true) {
                $zip->extractTo(dirname($outputPath));
                $zip->close();

                // Find the extracted extension file
                $extractedFile = dirname($outputPath).'/'.$this->getExtensionFilename($platform);

                if (File::exists($extractedFile) && $extractedFile !== $outputPath) {
                    File::move($extractedFile, $outputPath);
                }
            } else {
                throw new \RuntimeException('Failed to open zip archive');
            }
        } else {
            // No extraction needed, just move the file
            if ($archivePath !== $outputPath) {
                File::move($archivePath, $outputPath);
            }
        }
    }
}

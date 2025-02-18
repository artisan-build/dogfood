<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Git;

use ArtisanBuild\Bench\Actions\GetProjectAndPackagePaths;
use Illuminate\Support\Facades\Process;

class MakeCommit
{
    public function __construct(private readonly GetProjectAndPackagePaths $projectAndPackagePaths) {}

    public function __invoke(string $message): void
    {
        $paths = ($this->projectAndPackagePaths)();

        foreach ($paths as $path) {
            Process::path($path)->run("git commit -m {$message}");
        }
    }
}

<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Git;

use ArtisanBuild\Bench\Actions\GetProjectAndPackagePaths;
use Illuminate\Support\Facades\Process;

class StageAllChangedFiles
{
    public function __construct(private readonly GetProjectAndPackagePaths $projectAndPackagePaths) {}

    public function __invoke(): void
    {
        $paths = ($this->projectAndPackagePaths)();

        foreach ($paths as $path) {
            Process::path($path)->run('git add .');
        }
    }
}

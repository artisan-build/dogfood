<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Git;

use ArtisanBuild\Bench\Actions\GetProjectAndPackagePaths;
use Illuminate\Support\Facades\Process;

class CreateBranchInProjectAndPackages
{
    public function __construct(private readonly GetProjectAndPackagePaths $projectAndPackagePaths) {}

    public function __invoke(string $branch): void
    {
        $paths = ($this->projectAndPackagePaths)();

        foreach ($paths as $path) {
            Process::path($path)->run("git checkout -b {$branch}");
            throw_if(trim(Process::path($path)->run('git rev-parse --abbrev-ref HEAD')->output()) !== $branch, 'I think we failed to create the branch in '.$path);
        }
    }
}

<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Git;

use ArtisanBuild\Bench\Actions\GetProjectAndPackagePaths;

class EnsureProjectAndPackagesAreOnMain
{
    public function __construct(
        private readonly GetCurrentBranchName $currentBranchName,
        private readonly GetProjectAndPackagePaths $projectAndPackagePaths,
    ) {}

    public function __invoke(): void
    {
        $paths = ($this->projectAndPackagePaths)();

        foreach ($paths as $path) {
            throw_if(($this->currentBranchName)($path) !== 'main', 'You are not on the main branch in '.$path);
        }
    }
}

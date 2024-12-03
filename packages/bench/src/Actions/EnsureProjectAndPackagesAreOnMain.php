<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Actions;

class EnsureProjectAndPackagesAreOnMain
{
    public function __construct(
        private GetCurrentBranchName $currentBranchName,
        private GetProjectAndPackagePaths $projectAndPackagePaths,
    ) {}

    public function __invoke(): void
    {
        $paths = ($this->projectAndPackagePaths)();

        foreach ($paths as $path) {
            throw_if(($this->currentBranchName)($path) !== 'main', 'You are not on the main branch in ' . $path);
        }
    }
}

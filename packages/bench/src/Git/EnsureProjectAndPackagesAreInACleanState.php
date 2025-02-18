<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Git;

use ArtisanBuild\Bench\Actions\GetProjectAndPackagePaths;

readonly class EnsureProjectAndPackagesAreInACleanState
{
    public function __construct(
        private GetProjectAndPackagePaths $projectAndPackagePaths,
        private HasUncomittedChanges $uncomittedChanges,
        private HasUnpushedCommits $unpushedCommits,
    ) {}

    public function __invoke(): void
    {
        $paths = ($this->projectAndPackagePaths)();

        foreach ($paths as $path) {
            throw_if(
                ($this->uncomittedChanges)($path),
                'You have uncommitted changes in '.$path,
            );

            throw_if(
                ($this->unpushedCommits)($path),
                'You have un-pushed changes in '.$path,
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Git;

use ArtisanBuild\Bench\Actions\GetProjectAndPackagePaths;
use Illuminate\Support\Facades\Process;

readonly class PushChanges
{
    public function __construct(
        private GetProjectAndPackagePaths $projectAndPackagePaths,
        private HasUnpushedCommits $unpushedCommits,
    ) {}

    public function __invoke(): void
    {
        $paths = ($this->projectAndPackagePaths)();

        foreach ($paths as $path) {
            if (($this->unpushedCommits)($path)) {
                Process::path($path)->run('git push');
            }
        }
    }
}

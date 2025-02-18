<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Git;

use Illuminate\Support\Facades\Process;

class HasUnpushedCommits
{
    public function __invoke($path): bool
    {
        return ! blank(Process::path($path)->run('git log @{u}..')->output());
    }
}

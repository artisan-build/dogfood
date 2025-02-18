<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Git;

use Illuminate\Support\Facades\Process;

class GetCurrentBranchName
{
    public function __invoke(string $path): string
    {
        return trim(Process::path($path)->run('git rev-parse --abbrev-ref HEAD')->output());
    }
}

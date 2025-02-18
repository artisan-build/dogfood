<?php

namespace ArtisanBuild\Bench\Git;

use Illuminate\Support\Facades\Process;

class AddSubtreeToPackageDirectory
{
    public function __invoke(string $package, string $repo, string $branch): string
    {
        $process = Process::run("git subtree add --prefix packages/{$package} {$repo} {$branch} --squash");

        return $process->output();
    }
}

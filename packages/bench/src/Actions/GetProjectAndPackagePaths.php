<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Actions;

use Illuminate\Support\Facades\File;

class GetProjectAndPackagePaths
{
    public function __invoke(): array
    {
        $paths = [base_path()];

        foreach (File::directories(base_path('packages')) as $directory) {
            $paths[] = $directory;
        }

        return $paths;
    }
}

<?php

namespace ArtisanBuild\FluxThemes\Actions;

use Illuminate\Support\Facades\File;

class DetectPackagesWithTemplates
{
    public function __invoke()
    {
        return collect(File::directories(base_path('vendor/artisan-build')))
            ->filter(fn($directory) => File::isDirectory("{$directory}/resources/views"))
            ->map(fn($directory) => str_replace(base_path(), '../..', $directory));

    }
}

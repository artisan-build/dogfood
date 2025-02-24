<?php

namespace ArtisanBuild\FluxThemes\Actions;

use Illuminate\Support\Facades\File;

class DetectPackagesWithTemplates
{
    public function __invoke()
    {
        $vendor_directory = config('flux-themes.vendor_directory', base_path('vendor/artisan-build'));

        return collect(File::directories($vendor_directory))
            ->filter(fn ($directory) => File::isDirectory("{$directory}/resources/views"))
            ->map(fn ($directory) => str_replace(base_path(), '../..', $directory));

    }
}

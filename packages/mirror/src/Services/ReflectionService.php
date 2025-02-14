<?php

declare(strict_types=1);

namespace ArtisanBuild\Mirror\Services;

use Illuminate\Support\Manager;
use ArtisanBuild\Mirror\Ed\MirrorService;
use ArtisanBuild\Mirror\Daniel\Reflect;

class ReflectionService extends Manager
{
    public function createEdDriver()
    {
        return new MirrorService();
    }

    public function createDanielDriver()
    {
        return new Reflect();
    }

    public function getDefaultDriver()
    {
        return config('mirror.default_implementation');
    }
}

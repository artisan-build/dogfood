<?php

declare(strict_types=1);

namespace ArtisanBuild\Mirror\Facades;

use Illuminate\Support\Facades\Facade;

class Mirror extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mirror';
    }
}

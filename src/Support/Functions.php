<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Support;

use Illuminate\Support\Facades\Auth;

class Functions
{
    public static function can(string $event)
    {
        return Auth::user()->hallway_members->first()->role->can($event);
    }

}

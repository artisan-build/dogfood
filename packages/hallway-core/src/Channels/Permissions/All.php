<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Permissions;

class All
{
    public function __invoke(): true
    {
        return true;
    }
}

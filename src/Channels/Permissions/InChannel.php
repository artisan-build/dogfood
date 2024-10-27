<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Permissions;

use Illuminate\Support\Facades\Context;

class InChannel
{
    public function __invoke()
    {
        return Context::get('active_member')->inChannel();
    }

}

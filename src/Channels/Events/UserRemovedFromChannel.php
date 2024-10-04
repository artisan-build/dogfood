<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Events;

use Thunk\Verbs\Event;

class UserRemovedFromChannel extends Event
{
    public function handle(): void
    {
        // Most of yall don't get the picture unless the flash is on. - Lil Wayne
    }
}

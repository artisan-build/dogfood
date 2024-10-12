<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Events;

use ArtisanBuild\Jetstream\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class UserRequestedHandle extends Event
{
    #[StateId(UserState::class)]
    public int $user_id;

    public string $handle;

    public ?string $handle_history = null;
}

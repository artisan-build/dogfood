<?php

namespace ArtisanBuild\Adverbs\Events;

use ArtisanBuild\Adverbs\EventBus\DispatchesAsLaravelEvent;
use Thunk\Verbs\Event;

class DummyDeleted extends Event
{
    use DispatchesAsLaravelEvent;
}

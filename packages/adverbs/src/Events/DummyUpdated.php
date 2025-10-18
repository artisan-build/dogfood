<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\Events;

use ArtisanBuild\Adverbs\Traits\HandleDefinedInConfiguration;
use Thunk\Verbs\Event;

class DummyUpdated extends Event
{
    use HandleDefinedInConfiguration;
}

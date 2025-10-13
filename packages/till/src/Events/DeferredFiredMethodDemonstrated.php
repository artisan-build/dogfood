<?php

declare(strict_types=1);

namespace ArtisanBuild\Till\Events;

use ArtisanBuild\Till\Traits\AffectsAbilities;
use Thunk\Verbs\Event;

class DeferredFiredMethodDemonstrated extends Event
{
    use AffectsAbilities;
}

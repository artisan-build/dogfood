<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\Events;

use ArtisanBuild\Adverbs\Traits\ReturnsModelInstanceOnHandle;
use ArtisanBuild\Adverbs\Traits\SimpleApply;
use Thunk\Verbs\Event;

class DummyCreated extends Event
{
    use ReturnsModelInstanceOnHandle;
    use SimpleApply;

    public string $name;

    public string $description;

    public array $metadata;
}

<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\States;

use Thunk\Verbs\State;

class DummyState extends State
{
    public string $name;

    public string $description;

    public array $metadata;
}

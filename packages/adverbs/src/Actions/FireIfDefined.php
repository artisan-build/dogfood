<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\Actions;

class FireIfDefined
{
    public function __invoke(string $event, array $properties): void
    {
        if (class_exists($event)) {
            $event::fire(...$properties);
        }
    }
}

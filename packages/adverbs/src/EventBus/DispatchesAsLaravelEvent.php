<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\EventBus;

trait DispatchesAsLaravelEvent
{
    public function fired()
    {
        event(new VerbsEvent(static::class, (array) $this));
    }
}

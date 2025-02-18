<?php

namespace ArtisanBuild\Adverbs\EventBus;

trait DispatchesAsLaravelEvent
{
    public function fired()
    {
        VerbsEvent::dispatch(static::class, (array) $this);
    }
}

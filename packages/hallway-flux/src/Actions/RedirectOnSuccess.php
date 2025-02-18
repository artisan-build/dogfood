<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Actions;

use ArtisanBuild\Hallway\Channels\Events\CommunityChannelCreated;
use ArtisanBuild\VerbsFlux\Contracts\RedirectsOnSuccess;

class RedirectOnSuccess implements RedirectsOnSuccess
{
    public function __invoke(string $event, mixed $success): ?string
    {
        return match ($event) {
            CommunityChannelCreated::class => route('hallway-flux.channel', ['channel' => $success->id]),
            default => null,
        };
    }
}

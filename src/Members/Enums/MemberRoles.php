<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Enums;

use ArtisanBuild\FatEnums\Attributes\VerbsCan;
use ArtisanBuild\Hallway\Calendar\Events\GatheringCancelled;
use ArtisanBuild\Hallway\Calendar\Events\GatheringCreated;
use ArtisanBuild\Hallway\Calendar\Events\GatheringPublished;
use ArtisanBuild\Hallway\Calendar\Events\GatheringUpdated;
use ArtisanBuild\Hallway\Channels\Events\ChannelCreated;
use ArtisanBuild\Hallway\Channels\Events\ChannelNameChanged;
use ArtisanBuild\Hallway\Channels\Events\ChannelTypeChanged;
use Illuminate\Support\Str;
use ReflectionClassConstant;

enum MemberRoles: int
{
    // Human Roles
    #[VerbsCan([
        ChannelCreated::class,
        ChannelNameChanged::class,
        ChannelTypeChanged::class,
        GatheringCreated::class,
        GatheringUpdated::class,
        GatheringPublished::class,
        GatheringCancelled::class,
    ])]
    case Owner = 0;

    #[VerbsCan([
        ChannelCreated::class,
        ChannelNameChanged::class,
        ChannelTypeChanged::class,
        GatheringCreated::class,
        GatheringUpdated::class,
        GatheringPublished::class,
        GatheringCancelled::class,
    ])]
    case Admin = 1;

    #[VerbsCan([

    ])]
    case Moderator = 2;

    #[VerbsCan([
    ])]
    case Member = 3;

    // Bot Roles
    case ModeratorBot = 4;
    case ReadWriteBot = 5;
    case ReadBot = 6;

    public function isBot(): bool
    {
        // TODO: Is this faster than a match statement or in_array or something else?
        return Str::endsWith('Bot', $this->name);
    }

    public function can(string $event): bool
    {
        $events = (new ReflectionClassConstant($this, $this->name))
            ->getAttributes(VerbsCan::class)[0]->newInstance()->events;

        return in_array($event, $events, true);
    }

}

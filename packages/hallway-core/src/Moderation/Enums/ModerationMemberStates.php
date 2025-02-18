<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Moderation\Enums;

enum ModerationMemberStates: int
{
    case Active = 0;
    case Limited = 10;
    case LimitAppealed = 11;
    case Suspended = 20;
    case SuspensionAppealed = 21;

    public function hasCommunityWritePrivileges(): bool
    {
        return self::Active === $this;
    }

}

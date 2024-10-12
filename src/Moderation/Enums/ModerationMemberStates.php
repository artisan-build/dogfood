<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Moderation\Enums;

enum ModerationMemberStates: string
{
    case Active = 'Active';
    case Limited = 'Limited';
    case Suspended = 'Suspended';
    case LimitAppealed = 'LimitAppealed';
    case SuspensionAppealed = 'SuspensionAppealed';

}

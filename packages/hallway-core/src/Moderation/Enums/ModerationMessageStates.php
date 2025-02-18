<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Moderation\Enums;

enum ModerationMessageStates: int
{
    case None = 0; // No moderation action has been taken on this message
    case Reported = 1; // User or AI reported as possible violation
    case Okay = 2; // Moderator accepted message as okay
    case Warning = 3; // Moderator applied warning cover
    case Removed = 4; // Moderator removed the message

}

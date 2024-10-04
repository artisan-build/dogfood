<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Moderation\Enums;

enum ModerationMessageStates
{
    case None; // No moderation action has been taken on this message
    case Reported; // User or AI reported as possible violation
    case Okay; // Moderator accepted message as okay
    case Warning; // Moderator applied warning cover
    case Removed; // Moderator removed the message


}

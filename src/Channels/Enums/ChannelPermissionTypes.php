<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Enums;

enum ChannelPermissionTypes: string
{
    case Read = 'read';
    case Write = 'write';
    case Comment = 'comment';
    case Invite = 'invite';
    case Join = 'join';
}

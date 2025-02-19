<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Testing\Enums;

enum UserRoles: int
{
    case Owner = 0;
    case Admin = 1;
    case User = 2;
}

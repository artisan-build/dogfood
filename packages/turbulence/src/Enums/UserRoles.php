<?php

namespace ArtisanBuild\Turbulence\Enums;

enum UserRoles: string
{
    case Owner = 'owner';
    case SuperAdmin = 'super-admin';
    case Admin = 'admin';
    case User = 'user';

    public function canImpersonate(): bool
    {
        return $this !== self::User;
    }

    public function canCreateAdmin(): bool
    {
        return $this === self::Owner || $this === self::SuperAdmin;
    }
}

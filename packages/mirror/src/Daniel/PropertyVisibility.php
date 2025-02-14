<?php

namespace ArtisanBuild\Mirror\Daniel;

use ReflectionProperty;

enum PropertyVisibility: string
{
    case PUBLIC = 'public';
    case PROTECTED = 'protected';
    case PRIVATE = 'private';

    public static function fromReflectionProperty(ReflectionProperty $prop): static
    {
        return match (true) {
            $prop->isPublic() => self::PUBLIC,
            $prop->isProtected() => self::PROTECTED,
            $prop->isPrivate() => self::PRIVATE,
            default => throw new \Exception('Unknown property visibility'),
        };
    }
}

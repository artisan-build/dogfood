<?php

namespace ArtisanBuild\FluxThemes\Enums;

enum Grays: string
{
    case Gray = 'gray';
    case Neutral = 'neutral';
    case Slate = 'slate';
    case Stone = 'stone';
    case Zinc = '';

    public function config(): string
    {
        return $this === self::Zinc ? '' : "zinc: colors.{$this->value},";
    }
}

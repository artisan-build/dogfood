<?php

namespace ArtisanBuild\FluxThemes\Enums;

use ArtisanBuild\FluxThemes\Theme;
use Faker\Provider\Base;

enum Colors: string
{
    case Base = 'base';

    case Red = 'red';
    case Orange = 'orange';
    case Amber = 'amber';
    case Yellow = 'yellow';
    case Lime = 'lime';
    case Green = 'green';
    case Emerald = 'emerald';
    case Teal = 'teal';
    case Cyan = 'cyan';
    case Sky = 'sky';
    case Blue = 'blue';
    case Indigo = 'indigo';
    case Violet = 'violet';
    case Purple = 'purple';
    case Fuchsia = 'fuchsia';
    case Pink = 'pink';
    case Rose = 'rose';

    public function config(): array
    {
        return match ($this) {
            self::Base => [
                'color_accent' => [
                    'light' => '--color-zinc-800',
                    'dark' => '--color-white',
                ],
                'color_accent_content' => [
                    'light' => '--color-zinc-800',
                    'dark' => '--color-white',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-white',
                    'dark' => '--color-zinc-800',
                ],
                'gray' => Grays::Zinc,
            ],
            self::Red => [
                'color_accent' => [
                    'light' => '--color-red-500',
                    'dark' => '--color-red-500',
                ],
                'color_accent_content' => [
                    'light' => '--color-red-600',
                    'dark' => '--color-red-400',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-white',
                    'dark' => '--color-white',
                ],
                'gray' => Grays::Zinc,
            ],
            self::Orange => [
                'color_accent' => [
                    'light' => '--color-orange-500',
                    'dark' => '--color-orange-400',
                ],
                'color_accent_content' => [
                    'light' => '--color-orange-600',
                    'dark' => '--color-orange-400',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-white',
                    'dark' => '--color-orange-950',
                ],
                'gray' => Grays::Neutral,
            ],
            self::Amber => [
                'color_accent' => [
                    'light' => '--color-amber-400',
                    'dark' => '--color-orange-400',
                ],
                'color_accent_content' => [
                    'light' => '--color-amber-600',
                    'dark' => '--color-amber-400',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-amber-950',
                    'dark' => '--color-amber-950',
                ],
                'gray' => Grays::Neutral,
            ],
            self::Yellow => [
                'color_accent' => [
                    'light' => '--color-yellow-400',
                    'dark' => '--color-yellow-400',
                ],
                'color_accent_content' => [
                    'light' => '--color-yellow-600',
                    'dark' => '--color-yellow-400',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-yellow-950',
                    'dark' => '--color-yellow-950',
                ],
                'gray' => Grays::Stone,
            ],
            self::Lime => [
                'color_accent' => [
                    'light' => '--color-lime-400',
                    'dark' => '--color-lime-400',
                ],
                'color_accent_content' => [
                    'light' => '--color-lime-600',
                    'dark' => '--color-lime-400',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-lime-900',
                    'dark' => '--color-lime-950',
                ],
                'gray' => Grays::Zinc,
            ],
            self::Green => [
                'color_accent' => [
                    'light' => '--color-green-600',
                    'dark' => '--color-green-600',
                ],
                'color_accent_content' => [
                    'light' => '--color-green-600',
                    'dark' => '--color-green-400',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-white',
                    'dark' => '--color-white',
                ],
                'gray' => Grays::Zinc,
            ],
            self::Emerald => [
                'color_accent' => [
                    'light' => '--color-emerald-600',
                    'dark' => '--color-emerald-600',
                ],
                'color_accent_content' => [
                    'light' => '--color-emerald-600',
                    'dark' => '--color-emerald-400',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-white',
                    'dark' => '--color-white',
                ],
                'gray' => Grays::Zinc,
            ],
            self::Teal => [
                'color_accent' => [
                    'light' => '--color-teal-600',
                    'dark' => '--color-teal-600',
                ],
                'color_accent_content' => [
                    'light' => '--color-teal-600',
                    'dark' => '--color-teal-400',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-white',
                    'dark' => '--color-white',
                ],
                'gray' => Grays::Gray,
            ],
            self::Cyan => [
                'color_accent' => [
                    'light' => '--color-cyan-600',
                    'dark' => '--color-cyan-600',
                ],
                'color_accent_content' => [
                    'light' => '--color-cyan-600',
                    'dark' => '--color-cyan-400',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-white',
                    'dark' => '--color-white',
                ],
                'gray' => Grays::Gray,
            ],
            self::Sky => [
                'color_accent' => [
                    'light' => '--color-sky-600',
                    'dark' => '--color-sky-600',
                ],
                'color_accent_content' => [
                    'light' => '--color-sky-600',
                    'dark' => '--color-sky-400',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-white',
                    'dark' => '--color-white',
                ],
                'gray' => Grays::Gray,
            ],
            self::Blue => [
                'color_accent' => [
                    'light' => '--color-blue-500',
                    'dark' => '--color-blue-500',
                ],
                'color_accent_content' => [
                    'light' => '--color-blue-600',
                    'dark' => '--color-blue-400',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-white',
                    'dark' => '--color-white',
                ],
                'gray' => Grays::Slate,
            ],
            self::Indigo => [
                'color_accent' => [
                    'light' => '--color-indigo-500',
                    'dark' => '--color-indigo-500',
                ],
                'color_accent_content' => [
                    'light' => '--color-indigo-600',
                    'dark' => '--color-indigo-300',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-white',
                    'dark' => '--color-white',
                ],
                'gray' => Grays::Slate,
            ],
            self::Violet => [
                'color_accent' => [
                    'light' => '--color-violet-500',
                    'dark' => '--color-violet-500',
                ],
                'color_accent_content' => [
                    'light' => '--color-violet-600',
                    'dark' => '--color-violet-400',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-white',
                    'dark' => '--color-white',
                ],
                'gray' => Grays::Gray,
            ],
            self::Purple => [
                'color_accent' => [
                    'light' => '--color-purple-500',
                    'dark' => '--color-purple-500',
                ],
                'color_accent_content' => [
                    'light' => '--color-purple-600',
                    'dark' => '--color-purple-300',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-white',
                    'dark' => '--color-white',
                ],
                'gray' => Grays::Gray,
            ],
            self::Fuchsia => [
                'color_accent' => [
                    'light' => '--color-fuchsia-600',
                    'dark' => '--color-fuchsia-600',
                ],
                'color_accent_content' => [
                    'light' => '--color-fuchsia-600',
                    'dark' => '--color-fuchsia-400',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-white',
                    'dark' => '--color-white',
                ],
                'gray' => Grays::Zinc,
            ],
            self::Pink => [
                'color_accent' => [
                    'light' => '--color-pink-600',
                    'dark' => '--color-pink-600',
                ],
                'color_accent_content' => [
                    'light' => '--color-pink-600',
                    'dark' => '--color-pink-400',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-white',
                    'dark' => '--color-white',
                ],
                'gray' => Grays::Zinc,
            ],
            self::Rose => [
                'color_accent' => [
                    'light' => '--color-rose-500',
                    'dark' => '--color-rose-500',
                ],
                'color_accent_content' => [
                    'light' => '--color-rose-500',
                    'dark' => '--color-rose-400',
                ],
                'color_accent_foreground' => [
                    'light' => '--color-white',
                    'dark' => '--color-white',
                ],
                'gray' => Grays::Zinc,
            ],
        };
    }

    public function set(Theme $theme): Theme
    {
        foreach ($this->config() as $key => $value) {
            $theme->{$key} = $value;
        }

        return $theme;
    }
}

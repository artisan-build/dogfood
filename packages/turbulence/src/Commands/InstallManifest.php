<?php

namespace ArtisanBuild\Turbulence\Commands;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class InstallManifest
{
    public static function files(): Collection
    {
        return collect([
            'config' => [
                __DIR__.'/../../config/turbulence.php' => [
                    'destination' => config_path('turbulence.php'),
                    'rector' => [],
                    'replace' => [
                        \ArtisanBuild\Turbulence\Models\UserModel::class => \App\Models\User::class,
                        \ArtisanBuild\Turbulence\Models\Account::class => '\App\Models\Account',
                        "'installed' => false" => "'installed' => true",
                    ],
                    'undo' => fn () => File::delete(config_path('turbulence.php')),
                ],
            ],
            'models' => [
                __DIR__.'/../Models/Account.php' => [
                    'destination' => app_path('Models/Account.php'),
                    'rector' => [],
                    'replace' => [
                        'ArtisanBuild\Turbulence\Models' => 'App\Models',
                    ],
                    'undo' => fn () => File::delete(app_path('Models/Account.php')),
                ],
                __DIR__.'/../Models/AccountProfile.php' => [
                    'destination' => app_path('Models/AccountProfile.php'),
                    'rector' => [],
                    'replace' => [
                        'ArtisanBuild\Turbulence\Models' => 'App\Models',
                    ],
                    'undo' => fn () => File::delete(app_path('Models/AccountProfile.php')),
                ],
            ],
            'traits' => [],
        ]);
    }

    public static function replace(): array
    {
        return [
            '/**
* @internal
*/' => '',
            '/** @internal */' => '',
            '@internal' => '',
        ];
    }
}

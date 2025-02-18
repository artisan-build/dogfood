<?php

declare(strict_types=1);

use ArtisanBuild\Hallway\Members\Middleware\GetCurrentActiveMemberFromSession;

describe('all events extend the verbs event class', function (): void {

    $directory = __DIR__.'/../../src/';

    $directories = array_filter(glob($directory.'/*'), 'is_dir');

    $directoryNames = array_map('basename', $directories);

    $models = $events = $traits = [];
    foreach ($directoryNames as $name) {
        $models[] = "ArtisanBuild\Hallway\\{$name}\Models";
        $events[] = "ArtisanBuild\Hallway\\{$name}\Events";
        $traits[] = "ArtisanBuild\Hallway\\{$name}\Traits";
    }

    foreach ($models as $model) {
        arch()
            ->expect($model)
            ->not
            ->toBeUsed()
            ->ignoring(array_merge($events, $traits, [App\Models\User::class, GetCurrentActiveMemberFromSession::class]));
    }

});

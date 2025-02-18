<?php

declare(strict_types=1);

describe('all events extend the verbs event class', function (): void {

    $directory = __DIR__ . '/../../src/';

    $directories = array_filter(glob($directory . '/*'), 'is_dir');

    $directoryNames = array_map('basename', $directories);

    foreach ($directoryNames as $name) {
        arch()
            ->expect("ArtisanBuild\Hallway\\{$name}\Events")
            ->toExtend(Thunk\Verbs\Event::class);
    }

});

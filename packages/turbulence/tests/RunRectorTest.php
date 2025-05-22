<?php

use ArtisanBuild\Turbulence\Rectors\RemoveInternalDocblockRector;
use ArtisanBuild\Turbulence\Support\RunRector;
use Illuminate\Support\Facades\File;

describe('the rectors', function (): void {
    it('removes internal from the docblock', function (): void {
        $before = File::get(__DIR__.'/files/InternalClass.php');
        expect($before)->toContain('@internal');

        RunRector::rule(rule: RemoveInternalDocblockRector::class)
            ->on(path: __DIR__.'/files/InternalClass.php');

        $after = File::get(__DIR__.'/files/InternalClass.php');
        expect($after)->not->toContain('@internal');

        File::put(__DIR__.'/files/InternalClass.php', $before);

    });
})->skip();

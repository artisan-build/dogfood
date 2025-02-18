<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The status command', function (): void {

    it('calls the show command correctly', function (): void {
        GH::status()->show();
        Process::assertRan('gh status');
    });
});

<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The browse command', function (): void {

    it('calls the open command correctly without options', function (): void {
        GH::browse('artisan-build/test')->open();
        Process::assertRan('gh browse artisan-build/test');
    });

    it('calls the open command correctly with a branch', function (): void {
        GH::browse('artisan-build/test')->open('main');
        Process::assertRan("gh browse artisan-build/test --branch 'main'");
    });

    it('calls the open command correctly with a commit', function (): void {
        GH::browse('artisan-build/test')->open(null, 'abc123');
        Process::assertRan("gh browse artisan-build/test --commit 'abc123'");
    });

    it('calls the open command correctly with both branch and commit', function (): void {
        GH::browse('artisan-build/test')->open('main', 'abc123');
        Process::assertRan("gh browse artisan-build/test --branch 'main' --commit 'abc123'");
    });
});

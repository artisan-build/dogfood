<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The run command', function (): void {

    it('calls the list command correctly', function (): void {
        GH::run('artisan-build/test')->list();
        Process::assertRan('gh run list --repo artisan-build/test');
    });

    it('calls the view command correctly', function (): void {
        GH::run('artisan-build/test')->view('123');
        Process::assertRan('gh run view 123 --repo artisan-build/test');
    });

    it('calls the rerun command correctly', function (): void {
        GH::run('artisan-build/test')->rerun('123');
        Process::assertRan('gh run rerun 123 --repo artisan-build/test');
    });

    it('calls the cancel command correctly', function (): void {
        GH::run('artisan-build/test')->cancel('123');
        Process::assertRan('gh run cancel 123 --repo artisan-build/test');
    });

    it('calls the download command correctly with directory', function (): void {
        GH::run('artisan-build/test')->download('123', 'artifacts/');
        Process::assertRan("gh run download 123 --repo artisan-build/test --dir 'artifacts/'");
    });

    it('calls the download command correctly without directory', function (): void {
        GH::run('artisan-build/test')->download('123');
        Process::assertRan('gh run download 123 --repo artisan-build/test');
    });
});

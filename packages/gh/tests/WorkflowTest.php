<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The workflow command', function (): void {

    it('calls the enable command correctly', function (): void {
        GH::workflow('artisan-build/test')->enable('build.yml');
        Process::assertRan('gh workflow enable build.yml --repo artisan-build/test');
    });

    it('calls the disable command correctly', function (): void {
        GH::workflow('artisan-build/test')->disable('build.yml');
        Process::assertRan('gh workflow disable build.yml --repo artisan-build/test');
    });

    it('calls the list command correctly', function (): void {
        GH::workflow('artisan-build/test')->list();
        Process::assertRan('gh workflow list --repo artisan-build/test');
    });

    it('calls the run command correctly with ref', function (): void {
        GH::workflow('artisan-build/test')->run('build.yml', 'main');
        Process::assertRan('gh workflow run build.yml --repo artisan-build/test --ref main');
    });

    it('calls the run command correctly without ref', function (): void {
        GH::workflow('artisan-build/test')->run('build.yml');
        Process::assertRan('gh workflow run build.yml --repo artisan-build/test');
    });

    it('calls the view command correctly', function (): void {
        GH::workflow('artisan-build/test')->view('build.yml');
        Process::assertRan('gh workflow view build.yml --repo artisan-build/test');
    });
});

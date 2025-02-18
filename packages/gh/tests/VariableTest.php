<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The variable command', function (): void {

    it('calls the list command correctly', function (): void {
        GH::variable('artisan-build/test')->list();
        Process::assertRan('gh variable list --repo artisan-build/test');
    });

    it('calls the set command correctly', function (): void {
        GH::variable('artisan-build/test')->set('ENV_VAR', 'value123');
        Process::assertRan("gh variable set ENV_VAR --body 'value123' --repo artisan-build/test");
    });

    it('calls the get command correctly', function (): void {
        GH::variable('artisan-build/test')->get('ENV_VAR');
        Process::assertRan('gh variable get ENV_VAR --repo artisan-build/test');
    });

    it('calls the delete command correctly', function (): void {
        GH::variable('artisan-build/test')->delete('ENV_VAR');
        Process::assertRan('gh variable delete ENV_VAR --repo artisan-build/test');
    });
});

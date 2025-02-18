<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The ruleset command', function (): void {

    it('calls the list command correctly', function (): void {
        GH::ruleset('artisan-build/test')->list();
        Process::assertRan('gh ruleset list --repo artisan-build/test');
    });

    it('calls the view command correctly', function (): void {
        GH::ruleset('artisan-build/test')->view('123');
        Process::assertRan('gh ruleset view 123 --repo artisan-build/test');
    });

    it('calls the create command correctly with parameters', function (): void {
        GH::ruleset('artisan-build/test')->create([
            'name' => 'My Ruleset',
            'enforcement' => 'active',
        ]);
        Process::assertRan("gh ruleset create --repo artisan-build/test --name 'My Ruleset' --enforcement 'active'");
    });

    it('calls the edit command correctly with parameters', function (): void {
        GH::ruleset('artisan-build/test')->edit('123', [
            'name' => 'Updated Ruleset',
            'enforcement' => 'inactive',
        ]);
        Process::assertRan("gh ruleset edit 123 --repo artisan-build/test --name 'Updated Ruleset' --enforcement 'inactive'");
    });

    it('calls the delete command correctly', function (): void {
        GH::ruleset('artisan-build/test')->delete('123');
        Process::assertRan('gh ruleset delete 123 --repo artisan-build/test');
    });

    it('calls the enable command correctly', function (): void {
        GH::ruleset('artisan-build/test')->enable('123');
        Process::assertRan('gh ruleset enable 123 --repo artisan-build/test');
    });

    it('calls the disable command correctly', function (): void {
        GH::ruleset('artisan-build/test')->disable('123');
        Process::assertRan('gh ruleset disable 123 --repo artisan-build/test');
    });
});

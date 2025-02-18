<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The codespace command', function (): void {

    it('calls the list command correctly', function (): void {
        GH::codespace()->list();
        Process::assertRan('gh codespace list');
    });

    it('calls the create command correctly with no options', function (): void {
        GH::codespace()->create();
        Process::assertRan('gh codespace create');
    });

    it('calls the create command correctly with repository', function (): void {
        GH::codespace()->create('artisan-build/test');
        Process::assertRan("gh codespace create --repo 'artisan-build/test'");
    });

    it('calls the create command correctly with repository and branch', function (): void {
        GH::codespace()->create('artisan-build/test', 'main');
        Process::assertRan("gh codespace create --repo 'artisan-build/test' --branch 'main'");
    });

    it('calls the create command correctly with repository, branch, and machine', function (): void {
        GH::codespace()->create('artisan-build/test', 'main', 'standardLinux');
        Process::assertRan("gh codespace create --repo 'artisan-build/test' --branch 'main' --machine 'standardLinux'");
    });

    it('calls the delete command correctly', function (): void {
        GH::codespace()->delete('my-codespace');
        Process::assertRan("gh codespace delete --codespace 'my-codespace'");
    });

    it('calls the stop command correctly', function (): void {
        GH::codespace()->stop('my-codespace');
        Process::assertRan("gh codespace stop --codespace 'my-codespace'");
    });

    it('calls the logs command correctly', function (): void {
        GH::codespace()->logs('my-codespace');
        Process::assertRan("gh codespace logs --codespace 'my-codespace'");
    });

    it('calls the ports command correctly', function (): void {
        GH::codespace()->ports('my-codespace');
        Process::assertRan("gh codespace ports --codespace 'my-codespace'");
    });
});

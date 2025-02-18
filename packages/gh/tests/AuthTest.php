<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The auth command', function (): void {

    it('calls the login command correctly without options', function (): void {
        GH::auth()->login();
        Process::assertRan('gh auth login');
    });

    it('calls the login command correctly with hostname', function (): void {
        GH::auth()->login('github.com');
        Process::assertRan("gh auth login --hostname 'github.com'");
    });

    it('calls the login command correctly with scopes', function (): void {
        GH::auth()->login(null, 'repo,read:org');
        Process::assertRan("gh auth login --scopes 'repo,read:org'");
    });

    it('calls the login command correctly with web flag', function (): void {
        GH::auth()->login(null, null, true);
        Process::assertRan('gh auth login --web');
    });

    it('calls the logout command correctly without options', function (): void {
        GH::auth()->logout();
        Process::assertRan('gh auth logout');
    });

    it('calls the logout command correctly with hostname', function (): void {
        GH::auth()->logout('github.com');
        Process::assertRan("gh auth logout --hostname 'github.com'");
    });

    it('calls the status command correctly without options', function (): void {
        GH::auth()->status();
        Process::assertRan('gh auth status');
    });

    it('calls the status command correctly with hostname', function (): void {
        GH::auth()->status('github.com');
        Process::assertRan("gh auth status --hostname 'github.com'");
    });

    it('calls the token command correctly', function (): void {
        GH::auth()->token();
        Process::assertRan('gh auth token');
    });
});

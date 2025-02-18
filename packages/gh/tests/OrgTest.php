<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The org command', function (): void {

    it('calls the list command correctly', function (): void {
        GH::org()->list();
        Process::assertRan('gh org list');
    });

    it('calls the view command correctly', function (): void {
        GH::org('artisan-build')->view();
        Process::assertRan('gh org view artisan-build');
    });

    it('calls the members command correctly', function (): void {
        GH::org('artisan-build')->members();
        Process::assertRan('gh org members artisan-build');
    });

    it('calls the invitations command correctly', function (): void {
        GH::org('artisan-build')->invitations();
        Process::assertRan('gh org invitations artisan-build');
    });

    it('calls the audit-log command correctly', function (): void {
        GH::org('artisan-build')->auditLog();
        Process::assertRan('gh org audit-log artisan-build');
    });
});

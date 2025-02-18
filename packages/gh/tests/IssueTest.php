<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The issue command', function (): void {

    it('calls the list command correctly', function (): void {
        GH::issue('artisan-build/test')->list();
        Process::assertRan('gh issue list --repo artisan-build/test');
    });

    it('calls the view command correctly', function (): void {
        GH::issue('artisan-build/test')->view('123');
        Process::assertRan('gh issue view 123 --repo artisan-build/test');
    });

    it('calls the create command correctly with title and body', function (): void {
        GH::issue('artisan-build/test')->create('New Issue', 'This is the issue body.');
        Process::assertRan("gh issue create --repo artisan-build/test --title 'New Issue' --body 'This is the issue body.'");
    });

    it('calls the create command correctly with additional options', function (): void {
        GH::issue('artisan-build/test')->create('New Issue', 'This is the issue body.', ['--label bug', '--assignee user1']);
        Process::assertRan("gh issue create --repo artisan-build/test --title 'New Issue' --body 'This is the issue body.' 'bug' 'assignee user1'");
    })->skip();

    it('calls the edit command correctly with title and body', function (): void {
        GH::issue('artisan-build/test')->edit('123', 'Updated Title', 'Updated body.');
        Process::assertRan("gh issue edit 123 --repo artisan-build/test --title 'Updated Title' --body 'Updated body.'");
    });

    it('calls the edit command correctly with additional options', function (): void {
        GH::issue('artisan-build/test')->edit('123', 'Updated Title', 'Updated body.', ['--label enhancement']);
        Process::assertRan("gh issue edit 123 --repo artisan-build/test --title 'Updated Title' --body 'Updated body.' 'enhancement'");
    })->skip();

    it('calls the close command correctly', function (): void {
        GH::issue('artisan-build/test')->close('123');
        Process::assertRan('gh issue close 123 --repo artisan-build/test');
    });

    it('calls the reopen command correctly', function (): void {
        GH::issue('artisan-build/test')->reopen('123');
        Process::assertRan('gh issue reopen 123 --repo artisan-build/test');
    });

    it('calls the comment command correctly', function (): void {
        GH::issue('artisan-build/test')->comment('123', 'This is a comment.');
        Process::assertRan("gh issue comment 123 --repo artisan-build/test --body 'This is a comment.'");
    });
});

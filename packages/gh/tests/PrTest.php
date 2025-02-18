<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The pr command', function (): void {

    it('calls the list command correctly', function (): void {
        GH::pr('artisan-build/test')->list();
        Process::assertRan('gh pr list --repo artisan-build/test');
    });

    it('calls the view command correctly without web flag', function (): void {
        GH::pr('artisan-build/test')->view('123');
        Process::assertRan('gh pr view 123 --repo artisan-build/test');
    });

    it('calls the view command correctly with web flag', function (): void {
        GH::pr('artisan-build/test')->view('123', true);
        Process::assertRan('gh pr view 123 --repo artisan-build/test --web');
    });

    it('calls the create command correctly with title and body', function (): void {
        GH::pr('artisan-build/test')->create('New PR', 'This is the body of the PR.');
        Process::assertRan("gh pr create --repo artisan-build/test --title 'New PR' --body 'This is the body of the PR.'");
    });

    it('calls the create command correctly with additional options', function (): void {
        GH::pr('artisan-build/test')->create('New PR', 'This is the body of the PR.', ['--base main', '--label bug']);
        Process::assertRan("gh pr create --repo artisan-build/test --title 'New PR' --body 'This is the body of the PR.' 'base main' 'label bug'");
    })->skip();

    it('calls the edit command correctly with title and body', function (): void {
        GH::pr('artisan-build/test')->edit('123', 'Updated Title', 'Updated body.');
        Process::assertRan("gh pr edit 123 --repo artisan-build/test --title 'Updated Title' --body 'Updated body.'");
    });

    it('calls the edit command correctly with additional options', function (): void {
        GH::pr('artisan-build/test')->edit('123', 'Updated Title', 'Updated body.', ['--add-label enhancement']);
        Process::assertRan("gh pr edit 123 --repo artisan-build/test --title 'Updated Title' --body 'Updated body.' 'add-label enhancement'");
    })->skip();

    it('calls the close command correctly', function (): void {
        GH::pr('artisan-build/test')->close('123');
        Process::assertRan('gh pr close 123 --repo artisan-build/test');
    });

    it('calls the reopen command correctly', function (): void {
        GH::pr('artisan-build/test')->reopen('123');
        Process::assertRan('gh pr reopen 123 --repo artisan-build/test');
    });

    it('calls the merge command correctly with default options', function (): void {
        GH::pr('artisan-build/test')->merge('123');
        Process::assertRan('gh pr merge 123 --repo artisan-build/test');
    });

    it('calls the merge command correctly with squash option', function (): void {
        GH::pr('artisan-build/test')->merge('123', true);
        Process::assertRan('gh pr merge 123 --repo artisan-build/test --squash');
    });

    it('calls the merge command correctly with rebase option', function (): void {
        GH::pr('artisan-build/test')->merge('123', false, true);
        Process::assertRan('gh pr merge 123 --repo artisan-build/test --rebase');
    });
});

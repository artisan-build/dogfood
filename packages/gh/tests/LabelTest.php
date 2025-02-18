<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The label command', function (): void {

    it('calls the list command correctly', function (): void {
        GH::label('artisan-build/test')->list();
        Process::assertRan('gh label list --repo artisan-build/test');
    });

    it('calls the create command correctly with name only', function (): void {
        GH::label('artisan-build/test')->create('bug');
        Process::assertRan("gh label create 'bug' --repo artisan-build/test");
    });

    it('calls the create command correctly with description', function (): void {
        GH::label('artisan-build/test')->create('bug', 'Indicates a bug');
        Process::assertRan("gh label create 'bug' --repo artisan-build/test --description 'Indicates a bug'");
    });

    it('calls the create command correctly with color', function (): void {
        GH::label('artisan-build/test')->create('bug', null, 'FF0000');
        Process::assertRan("gh label create 'bug' --repo artisan-build/test --color 'FF0000'");
    });

    it('calls the create command correctly with description and color', function (): void {
        GH::label('artisan-build/test')->create('bug', 'Indicates a bug', 'FF0000');
        Process::assertRan("gh label create 'bug' --repo artisan-build/test --description 'Indicates a bug' --color 'FF0000'");
    });

    it('calls the edit command correctly with new name', function (): void {
        GH::label('artisan-build/test')->edit('bug', 'fixed-bug');
        Process::assertRan("gh label edit 'bug' --repo artisan-build/test --name 'fixed-bug'");
    });

    it('calls the edit command correctly with new description', function (): void {
        GH::label('artisan-build/test')->edit('bug', null, 'Resolved issues');
        Process::assertRan("gh label edit 'bug' --repo artisan-build/test --description 'Resolved issues'");
    });

    it('calls the edit command correctly with new color', function (): void {
        GH::label('artisan-build/test')->edit('bug', null, null, '00FF00');
        Process::assertRan("gh label edit 'bug' --repo artisan-build/test --color '00FF00'");
    });

    it('calls the edit command correctly with all options', function (): void {
        GH::label('artisan-build/test')->edit('bug', 'fixed-bug', 'Resolved issues', '00FF00');
        Process::assertRan("gh label edit 'bug' --repo artisan-build/test --name 'fixed-bug' --description 'Resolved issues' --color '00FF00'");
    });

    it('calls the delete command correctly', function (): void {
        GH::label('artisan-build/test')->delete('bug');
        Process::assertRan("gh label delete 'bug' --repo artisan-build/test");
    });
})->skip();

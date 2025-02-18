<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The gist command', function (): void {

    it('calls the list command correctly', function (): void {
        GH::gist()->list();
        Process::assertRan('gh gist list');
    });

    it('calls the view command correctly without raw option', function (): void {
        GH::gist()->view('abc123');
        Process::assertRan('gh gist view abc123');
    });

    it('calls the view command correctly with raw option', function (): void {
        GH::gist()->view('abc123', true);
        Process::assertRan('gh gist view abc123 --raw');
    });

    it('calls the create command correctly without description or public', function (): void {
        GH::gist()->create(['file1.txt']);
        Process::assertRan("gh gist create 'file1.txt'");
    });

    it('calls the create command correctly with description', function (): void {
        GH::gist()->create(['file1.txt', 'file2.txt'], 'Test description');
        Process::assertRan("gh gist create 'file1.txt' 'file2.txt' --desc 'Test description'");
    });

    it('calls the create command correctly with public visibility', function (): void {
        GH::gist()->create(['file1.txt'], null, true);
        Process::assertRan("gh gist create 'file1.txt' --public");
    });

    it('calls the edit command correctly with description', function (): void {
        GH::gist()->edit('abc123', ['file1.txt'], 'Updated description');
        Process::assertRan("gh gist edit abc123 'file1.txt' --desc 'Updated description'");
    });

    it('calls the edit command correctly without description', function (): void {
        GH::gist()->edit('abc123', ['file1.txt']);
        Process::assertRan("gh gist edit abc123 'file1.txt'");
    });

    it('calls the delete command correctly', function (): void {
        GH::gist()->delete('abc123');
        Process::assertRan('gh gist delete abc123');
    });

    it('calls the fork command correctly', function (): void {
        GH::gist()->fork('abc123');
        Process::assertRan('gh gist fork abc123');
    });
});

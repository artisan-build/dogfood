<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The project command', function (): void {

    it('calls the list command correctly', function (): void {
        GH::project('artisan-build')->list();
        Process::assertRan('gh project list --owner artisan-build');
    });

    it('calls the view command correctly', function (): void {
        GH::project('artisan-build')->view(123);
        Process::assertRan('gh project view 123 --owner artisan-build');
    });

    it('calls the create command correctly', function (): void {
        GH::project('artisan-build')->create('New Project');
        Process::assertRan("gh project create --name 'New Project' --owner artisan-build");
    });

    it('calls the delete command correctly', function (): void {
        GH::project('artisan-build')->delete(123);
        Process::assertRan('gh project delete 123 --owner artisan-build');
    });

    it('calls the edit command correctly', function (): void {
        GH::project('artisan-build')->edit(123, [
            'name' => 'Updated Project',
            'description' => 'Updated description',
        ]);
        Process::assertRan("gh project edit 123 --owner artisan-build --name 'Updated Project' --description 'Updated description'");
    });

    it('calls the copy command correctly with a new name', function (): void {
        GH::project('artisan-build')->copy(123, 'Copied Project');
        Process::assertRan("gh project copy 123 --owner artisan-build --name 'Copied Project'");
    });

    it('calls the copy command correctly without a new name', function (): void {
        GH::project('artisan-build')->copy(123);
        Process::assertRan('gh project copy 123 --owner artisan-build');
    });
});

<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());
describe('The repo command', function (): void {
    it('calls the edit command correctly', function (): void {
        GH::repo('artisan-build/test')->edit();
        Process::assertRan('gh repo edit artisan-build/test');
    });

    it('calls the create command correctly', function (): void {
        GH::repo('artisan-build/test')->create();
        Process::assertRan('gh repo create artisan-build/test --add-readme --disable-issues --disable-wiki --license mit --public');
    });

    it('calls the archive command correctly', function (): void {
        GH::repo('artisan-build/test')->archive();
        Process::assertRan('gh repo archive artisan-build/test --yes');
    });

    it('calls the clone command correctly', function (): void {
        GH::repo('artisan-build/test')->clone('my-dir', 'https');
        Process::assertRan('gh repo clone https://github.com/artisan-build/test my-dir');
    });

    it('calls the delete command correctly', function (): void {
        GH::repo('artisan-build/test')->delete();
        Process::assertRan('gh repo delete artisan-build/test --yes');
    });

    it('calls the deploy-key add command correctly', function (): void {
        GH::repo('artisan-build/test')->deployKey('add', 'test-key', 'ssh-rsa AAAAB3Nza...');
        Process::assertRan('gh repo deploy-key add artisan-build/test test-key ssh-rsa AAAAB3Nza...');
    });

    it('calls the deploy-key delete command correctly', function (): void {
        GH::repo('artisan-build/test')->deployKey('delete', '12345');
        Process::assertRan('gh repo deploy-key delete artisan-build/test 12345');
    });

    it('calls the deploy-key list command correctly', function (): void {
        GH::repo('artisan-build/test')->deployKey('list');
        Process::assertRan('gh repo deploy-key list artisan-build/test');
    });

    it('calls the fork command correctly', function (): void {
        GH::repo('artisan-build/test')->fork('my-org');
        Process::assertRan('gh repo fork artisan-build/test --org my-org');
    });

    it('calls the rename command correctly', function (): void {
        GH::repo('artisan-build/test')->rename('new-name');
        Process::assertRan('gh repo rename artisan-build/test new-name');
    });

    it('calls the set-default command correctly', function (): void {
        GH::repo('artisan-build/test')->setDefault();
        Process::assertRan('gh repo set-default artisan-build/test');
    });

    it('calls the sync command correctly', function (): void {
        GH::repo('artisan-build/test')->sync('main', 'upstream');
        Process::assertRan('gh repo sync artisan-build/test --branch main --source upstream');
    });

    it('calls the unarchive command correctly', function (): void {
        GH::repo('artisan-build/test')->unarchive();
        Process::assertRan('gh repo unarchive artisan-build/test --yes');
    });

    it('calls the view command correctly', function (): void {
        GH::repo('artisan-build/test')->view('main', true);
        Process::assertRan('gh repo view artisan-build/test --branch main --web');
    });
});

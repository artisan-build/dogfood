<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The gpg-key command', function (): void {

    it('calls the list command correctly', function (): void {
        GH::gpgKey()->list();
        Process::assertRan('gh gpg-key list');
    });

    it('calls the add command correctly', function (): void {
        GH::gpgKey()->add('path/to/key.gpg');
        Process::assertRan("gh gpg-key add 'path/to/key.gpg'");
    });

    it('calls the delete command correctly', function (): void {
        GH::gpgKey()->delete('12345');
        Process::assertRan("gh gpg-key delete '12345'");
    });
});

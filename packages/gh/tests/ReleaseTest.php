<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The release command', function (): void {

    it('calls the list command correctly', function (): void {
        GH::release('artisan-build/test')->list();
        Process::assertRan('gh release list --repo artisan-build/test');
    });

    it('calls the view command correctly', function (): void {
        GH::release('artisan-build/test')->view('v1.0.0');
        Process::assertRan('gh release view v1.0.0 --repo artisan-build/test');
    });

    it('calls the create command correctly with title and notes', function (): void {
        GH::release('artisan-build/test')->create('v1.0.0', 'Release Title', 'Release Notes');
        Process::assertRan("gh release create v1.0.0 --title 'Release Title' --notes 'Release Notes' --repo artisan-build/test");
    });

    it('calls the create command correctly without title and notes', function (): void {
        GH::release('artisan-build/test')->create('v1.0.0');
        Process::assertRan('gh release create v1.0.0 --repo artisan-build/test');
    });

    it('calls the delete command correctly', function (): void {
        GH::release('artisan-build/test')->delete('v1.0.0');
        Process::assertRan('gh release delete v1.0.0 --repo artisan-build/test');
    });

    it('calls the uploadAsset command correctly with a label', function (): void {
        GH::release('artisan-build/test')->uploadAsset('v1.0.0', 'path/to/asset.zip', 'Asset Label');
        Process::assertRan("gh release upload v1.0.0 'path/to/asset.zip' --clabel 'Asset Label' --repo artisan-build/test");
    });

    it('calls the uploadAsset command correctly without a label', function (): void {
        GH::release('artisan-build/test')->uploadAsset('v1.0.0', 'path/to/asset.zip');
        Process::assertRan("gh release upload v1.0.0 'path/to/asset.zip' --repo artisan-build/test");
    });

    it('calls the edit command correctly with title and notes', function (): void {
        GH::release('artisan-build/test')->edit('v1.0.0', 'Updated Title', 'Updated Notes');
        Process::assertRan("gh release edit v1.0.0 --title 'Updated Title' --notes 'Updated Notes' --repo artisan-build/test");
    });

    it('calls the edit command correctly without title and notes', function (): void {
        GH::release('artisan-build/test')->edit('v1.0.0');
        Process::assertRan('gh release edit v1.0.0 --repo artisan-build/test');
    });
});

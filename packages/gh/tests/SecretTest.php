<?php

use ArtisanBuild\GH\GH;
use Illuminate\Support\Facades\Process;

beforeEach(fn () => Process::fake());

describe('The secret command', function (): void {

    it('calls the list command correctly without an environment', function (): void {
        GH::secret('artisan-build/test')->list();
        Process::assertRan('gh secret list --repo artisan-build/test');
    });

    it('calls the list command correctly with an environment', function (): void {
        GH::secret('artisan-build/test')->list('production');
        Process::assertRan("gh secret list --repo artisan-build/test --env 'production'");
    });

    it('calls the set command correctly without an environment', function (): void {
        GH::secret('artisan-build/test')->set('MY_SECRET', 'super-secret-value');
        Process::assertRan("gh secret set MY_SECRET --body 'super-secret-value' --repo artisan-build/test");
    });

    it('calls the set command correctly with an environment', function (): void {
        GH::secret('artisan-build/test')->set('MY_SECRET', 'super-secret-value', 'production');
        Process::assertRan("gh secret set MY_SECRET --body 'super-secret-value' --repo artisan-build/test --env 'production'");
    });

    it('calls the delete command correctly without an environment', function (): void {
        GH::secret('artisan-build/test')->delete('MY_SECRET');
        Process::assertRan('gh secret delete MY_SECRET --repo artisan-build/test');
    });

    it('calls the delete command correctly with an environment', function (): void {
        GH::secret('artisan-build/test')->delete('MY_SECRET', 'production');
        Process::assertRan("gh secret delete MY_SECRET --repo artisan-build/test --env 'production'");
    });
});

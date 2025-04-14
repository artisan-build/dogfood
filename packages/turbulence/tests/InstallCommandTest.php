<?php

use ArtisanBuild\Turbulence\Commands\InstallManifest;
use ArtisanBuild\Turbulence\Support\RunRector;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

afterEach(function () {
    InstallManifest::files()->each(function ($group) {
        foreach ($group as $file) {
            $file['undo']();
        }
    });
    Config::set('turbulence.installed', false);
});


describe('Installation command', function () {
    it('runs if not yet installed', function () {
        RunRector::fake();
        expect(Artisan::call('turbulence:install'))->toBe(Command::SUCCESS);
    });

    it('returns invalid if already installed and no force', function () {
        RunRector::fake();
        Config::set('turbulence.installed', true);
        expect(Artisan::call('turbulence:install'))->toBe(Command::INVALID);
    });

    it('runs if already installed and force is passed', function () {
        RunRector::fake();
        Config::set('turbulence.installed', true);
        expect(Artisan::call('turbulence:install', ['--force' => true]))->toBe(Command::SUCCESS);
    });

    it('sets the correct configuration values', function () {
        RunRector::fake();
        Artisan::call('turbulence:install');
        $config = require config_path('turbulence.php');

        expect($config['installed'])->toBeTrue()
            ->and($config['user_model'])->toBe("\App\Models\User")
            ->and($config['account_model'])->toBe("\App\Models\Account");
    });

    it('creates the models correctly', function () {
        RunRector::fake();
        Artisan::call('turbulence:install');
        expect(File::exists(app_path('Models/Account.php')))->toBeTrue()
            ->and(File::exists(app_path('Models/AccountProfile.php')))->toBeTrue()
            ->and(File::get(app_path('Models/Account.php')))->not->toContain('@internal')
            ->and(File::get(app_path('Models/AccountProfile.php')))->not->toContain('@internal');
    });

});

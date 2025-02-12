<?php

namespace ArtisanBuild\Till\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class CleanUpAfterTestingCommand extends Command
{
    protected $signature = 'till:test-cleanup';

    protected $description = 'Remove anything from the SubscriptionPlans directory inside the package that might have been created during a test';

    public function handle(): int
    {
        if (App::isProduction()) {
            $this->error('This cannot be run in production');

            return self::INVALID;
        }

        $expected = [
            'abilities' => [
                'AddSeats.php',
            ],
            'plans' => [
                'BasePlan.php',
            ],
        ];

        if (config('till.plan_path') !== base_path('packages/till/src/SubscriptionPlans')) {
            $this->error('We can only run this if the configuration is in its default state');

            return self::INVALID;
        }

        collect(File::files(config('till.plan_path').'/Abilities'))->each(function ($file) use ($expected): void {
            if (! in_array($file->getFilename(), $expected['abilities'], true)) {
                File::delete($file->getPathname());
            }
        });

        collect(File::files(config('till.plan_path')))->each(function ($file) use ($expected): void {
            if (! in_array($file->getFilename(), $expected['plans'], true)) {
                File::delete($file->getPathname());
            }
        });

        return self::SUCCESS;
    }
}

<?php

declare(strict_types=1);

namespace ArtisanBuild\SqliteVector\Tests;

use ArtisanBuild\SqliteVector\Providers\SqliteVectorServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Override;

class TestCase extends Orchestra
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'ArtisanBuild\\SqliteVector\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        config()->set('sqlite-vector.connection', 'testing');
    }

    protected function getPackageProviders($app)
    {
        return [
            SqliteVectorServiceProvider::class,
        ];
    }
}

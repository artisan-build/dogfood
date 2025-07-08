<?php

namespace ArtisanBuild\CodeChatClient\Tests;

use ArtisanBuild\CodeChatClient\Providers\CodeChatClientServiceProvider;
use Flux\FluxServiceProvider;
use FluxPro\FluxProServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            FluxServiceProvider::class,
            FluxProServiceProvider::class,
            CodeChatClientServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
    }
}

<?php

namespace ArtisanBuild\Turbulence\Support;

use Illuminate\Support\Facades\Process;

class RunRector
{
    public function __construct(private readonly string $rule) {}

    public static function rule(string $rule): RunRector
    {
        return new self($rule);
    }

    public static function fake()
    {
        Process::fake();
    }

    public function on(string $path): bool
    {
        return Process::run('vendor/bin/rector process '.$path.' --config '.__DIR__.'/../../rector.php --only="'.addslashes($this->rule).'"')->successful();
    }
}

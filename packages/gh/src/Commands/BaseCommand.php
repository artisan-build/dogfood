<?php

namespace ArtisanBuild\GH\Commands;

use Illuminate\Support\Facades\Process;

class BaseCommand implements GHCommand
{
    public array $options = [];

    public ?string $path = null;

    protected string $config_key = '';

    public function option($option): static
    {
        $this->options[] = $option;

        return $this;
    }

    public function getOptions($key): string
    {
        return $this->options === []
            ? implode(' ', config(implode('.', [$this->config_key, $key]), []))
            : implode(' ', $this->options);
    }

    public function path(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function executeCommand(string $command): string
    {
        $path = $this->path ?? getcwd();
        $process = Process::path($path)->run(trim($command));

        if (! $process->successful()) {
            return $process->errorOutput();
        }

        return $process->output();
    }
}

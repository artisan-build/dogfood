<?php

declare(strict_types=1);

namespace ArtisanBuild\Kibble\Commands;

use ArtisanBuild\Kibble\Actions\KibbleGitIgnore;
use Illuminate\Console\Command;

class UnlinkCommand extends Command
{
    protected $signature = 'kibble:unlink {repository}';

    protected $description = 'Temporarily remove a Kibble monorepo from your composer project';

    public function handle(): int
    {
        $repository = $this->argument('repository');

        $composer_json = file_get_contents('composer.json');
        $trailing_newline = str_ends_with($composer_json, "\n");

        $composer = json_decode($composer_json, true);

        if (! isset($composer['repositories'][$repository])) {
            $this->error("{$repository} repository not found in composer.json");

            return Command::FAILURE;
        }

        app(KibbleGitIgnore::class)();

        file_put_contents(
            filename: 'kibble.json',
            data: json_encode(
                value: [$repository => $composer['repositories'][$repository]],
                flags: JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            ).($trailing_newline ? "\n" : '')
        );

        unset($composer['repositories'][$repository]);

        file_put_contents(
            filename: 'composer.json',
            data: json_encode(
                value: $composer,
                flags: JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            ).($trailing_newline ? "\n" : '')
        );

        $this->info("{$repository} repository removed from composer.json");
        $this->info('Run `composer update` to remove the repository from your project.');

        return Command::SUCCESS;
    }
}

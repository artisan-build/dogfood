<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use JsonException;
use RuntimeException;

class StartIssue extends Command
{
    protected $signature = 'issue {number?}';

    protected $description = 'Create a branch and PR from an issue number';

    /**
     * @throws JsonException
     */
    public function handle(): int
    {
        $this->info('Checking to make sure you are ready to start a new issue...');
        $branch = exec('git rev-parse --abbrev-ref HEAD');

        if ($branch !== 'main') {
            $this->error('You are on the main branch. Please checkout the main branch and make sure it is up to date.');

            return self::INVALID;
        }

        exec('git fetch && git merge origin/main');

        $this->info('Making sure you have the GitHub CLI installed...');
        exec('gh --version', $gh);

        if (! str_contains(json_encode($gh, JSON_THROW_ON_ERROR), 'gh version')) {
            $this->error('You must have the GitHub CLI installed in order to use this command. See https://cli.github.com/');

            return self::FAILURE;
        }

        $this->info('Looking for new issue information...');

        $number = $this->argument('number');

        // If no number is provided, ask for one
        if (! $number) {
            $number = $this->ask('What is the issue number?');
        }

        // If the number is not numeric, ask if we want to create a new issue
        if (! is_numeric($number)) {
            if (! $this->confirm('Do you want to create a new issue?')) {
                $this->error('You need to either provide an issue number or confirm that you want to create a new issue.');

                return self::FAILURE;
            }
            $number = last(explode('/', exec('gh issue create --title="'.$number.'" --body="'.$number.'"')));
        }

        $this->info('Generating the branch name...');

        // Get the title of the issue from GitHub
        $issue = exec('gh issue view '.$number.' --repo='.$this->getRepo().' --json=title');

        $title = data_get(json_decode($issue, true, 512, JSON_THROW_ON_ERROR), 'title');

        $branch = $number.'-'.Str::slug($title);

        $this->info('Checking to see if the branch exists locally');
        exec("git show-branch {$branch}", $exists);

        if ($exists !== []) {
            exec("git checkout {$branch}");
            $this->info("Checked out existing branch {$branch}. Have fun!");

            return self::SUCCESS;
        }

        $this->info('Looking to see if the branch exists remotely');

        $remote = exec('git ls-remote --heads git@github.com:'.$this->getRepo().'.git '.$branch.' | wc -l');

        if ((int) $remote === 1) {
            exec("git checkout {$branch}");
            $this->info("Checked out existing branch {$branch}. Have fun!");

            return self::SUCCESS;
        }

        $this->info('Creating your new branch...');

        exec("git checkout -b {$branch} main");

        exec("git push --set-upstream origin {$branch}");

        $this->info('Making an initial commit to th new branch...');

        $this->info('Pushing an initial commit to the new branch...');
        exec('git add . && git commit --allow-empty -m "Started '.Str::headline($branch).'" && git push');

        $this->info('Opening a draft PR...');
        $title = '[ DRAFT ] '.Str::headline($branch);
        $body = "Resolves #{$number}";
        exec("gh pr create --assignee=@me --title=\"{$title}\" --body=\"{$body}\"");

        $this->info('Your branch and PR are created. Have a great time!');

        return self::SUCCESS;
    }

    protected function getRepo(): string
    {
        $command = 'git config --get remote.origin.url';
        $output = shell_exec($command);
        if (preg_match('/github\.com[:\/](.+)\/(.+)\.git/', $output, $matches)) {
            return $matches[1].'/'.$matches[2];
        }

        throw new RuntimeException('Unable to retrieve the GitHub repository information');
    }
}

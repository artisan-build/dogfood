<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Console\Commands;

use ArtisanBuild\ForgeClient\Console\Concerns\HandlesDefaultArguments;
use Illuminate\Console\Command;

class ActivateSslCertificateCommand extends Command
{
    use HandlesDefaultArguments;

    protected $signature = 'forge:activate-ssl-certificate
                            {site? : The site ID}
                            {server? : The server name or ID}
                            {organization? : The organization slug or ID}
                            {domain-record? : The domain record ID}';

    protected $description = 'Activate an SSL certificate (Note: SSL certificates are automatically activated upon installation)';

    public function handle(): int
    {
        $this->info('SSL certificates in Laravel Forge are automatically activated when they are created and installed.');
        $this->newLine();

        $this->comment('To create and activate an SSL certificate, use:');
        $this->line("  php artisan forge:create-ssl-certificate {$this->argument('organization')} {$this->argument('server')} {$this->argument('site')} {$this->argument('domain-record')}");
        $this->newLine();

        $this->comment('To view the current certificate status, use:');
        $this->line("  php artisan forge:get-ssl-certificate {$this->argument('organization')} {$this->argument('server')} {$this->argument('site')} {$this->argument('domain-record')}");
        $this->newLine();

        $this->warn('There is no separate activation step in the Forge API.');
        $this->warn('Creating/installing a certificate automatically activates it.');

        return self::SUCCESS;
    }
}

<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Console\Commands;

use ArtisanBuild\ForgeSdk\Console\Concerns\HandlesDefaultArguments;
use Illuminate\Console\Command;

class ListSslCertificatesCommand extends Command
{
    use HandlesDefaultArguments;

    protected $signature = 'forge:list-ssl-certificates
                            {organization? : The organization slug or ID}
                            {server? : The server name or ID}
                            {site? : The site ID}';

    protected $description = 'List SSL certificates for a site (requires domain-specific context)';

    public function handle(): int
    {
        $this->warn('SSL certificates in Laravel Forge are accessed per site domain.');
        $this->newLine();

        $this->info('To view SSL certificates, you need to:');
        $this->line('  1. First list domains for your site:');
        $this->line("     php artisan forge:list-domains {$this->argument('organization')} {$this->argument('server')} {$this->argument('site')}");
        $this->newLine();
        $this->line('  2. Then view the certificate for a specific domain:');
        $this->line("     php artisan forge:get-ssl-certificate {$this->argument('organization')} {$this->argument('server')} {$this->argument('site')} <domain-record-id>");
        $this->newLine();

        $this->comment('Each domain record can have its own SSL certificate.');
        $this->comment('Use the domain record ID from the list-domains output.');

        return self::SUCCESS;
    }
}

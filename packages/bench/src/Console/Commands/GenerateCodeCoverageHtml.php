<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Console\Commands;

use Illuminate\Console\Command;
use SebastianBergmann\CodeCoverage\Report\Html\Facade as HtmlReport;

class GenerateCodeCoverageHtml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-code-coverage-html';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Code Coverage HTML';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {

        $coverage = include base_path('coverage.php');

        (new HtmlReport)->process($coverage, public_path('coverage'));

        $this->info('See your coverage report at '.url('/coverage/index.html'));
    }
}

<?php

namespace App\Console\Commands;

use App\Jobs\GenerateMonthlyReport;
use Illuminate\Console\Command;

class GenerateReport extends Command
{
    protected $signature = 'report:generate-monthly {--month=}';
    protected $description = 'Generate monthly report';

    public function handle()
    {
        $this->info('Generating monthly report...');
        GenerateMonthlyReport::dispatch();
        $this->info('Report generation queued');
    }
}

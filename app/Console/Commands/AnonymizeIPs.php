<?php

namespace App\Console\Commands;

use App\Services\AnalyticsService;
use Illuminate\Console\Command;

class AnonymizeIPs extends Command
{
    protected $signature = 'analytics:anonymize-ips {--days=30 : Number of days to retain full IP addresses}';

    protected $description = 'Anonymize IP addresses in scan analytics older than the specified number of days';

    public function handle(AnalyticsService $analyticsService): int
    {
        $days = (int) $this->option('days');

        $this->info("Anonymizing IP addresses older than {$days} days...");

        $count = $analyticsService->anonymizeOldIps($days);

        $this->info("Done. Anonymized {$count} record(s).");

        return self::SUCCESS;
    }
}

<?php

namespace App\Jobs;

use App\Services\AnalyticsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnonymizeOldIPsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        $this->onQueue('maintenance');
    }

    public function handle(AnalyticsService $analyticsService): void
    {
        $count = $analyticsService->anonymizeOldIps();

        Log::info("Anonymized IP addresses for {$count} scan analytic records.");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("AnonymizeOldIPsJob failed: {$exception->getMessage()}");
    }
}

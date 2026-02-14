<?php

namespace App\Console\Commands;

use App\Jobs\GenerateQRCodeJob;
use App\Models\QrSlot;
use Illuminate\Console\Command;

class RegenerateQRCodes extends Command
{
    protected $signature = 'qr:regenerate-all {--user= : Regenerate for specific user}';

    protected $description = 'Regenerate QR codes for all slots or for a specific user';

    public function handle(): int
    {
        $userId = $this->option('user');

        $query = QrSlot::query();

        if ($userId) {
            $query->where('user_id', $userId);
            $this->info("Regenerating QR codes for user ID: {$userId}");
        } else {
            $this->info('Regenerating QR codes for all users...');
        }

        $slots = $query->get();

        if ($slots->isEmpty()) {
            $this->warn('No QR slots found.');
            return self::SUCCESS;
        }

        $this->info("Found {$slots->count()} QR slot(s) to regenerate.");

        $bar = $this->output->createProgressBar($slots->count());
        $bar->start();

        foreach ($slots as $slot) {
            GenerateQRCodeJob::dispatch($slot);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Dispatched {$slots->count()} QR code generation job(s) to the queue.");

        return self::SUCCESS;
    }
}

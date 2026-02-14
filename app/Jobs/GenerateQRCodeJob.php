<?php

namespace App\Jobs;

use App\Models\QrSlot;
use App\Services\QRCodeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateQRCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(
        public readonly QrSlot $slot,
    ) {
        $this->onQueue('qr-generation');
    }

    public function handle(QRCodeService $qrCodeService): void
    {
        $qrCodeService->generateAllFormats($this->slot);

        Log::info("QR code generated for slot [{$this->slot->short_code}].");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Failed to generate QR code for slot [{$this->slot->short_code}]: {$exception->getMessage()}");
    }
}

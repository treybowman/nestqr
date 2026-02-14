<?php

namespace App\Jobs;

use App\Models\QrSlot;
use App\Services\QRCodeService;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class GenerateQRCodeJob
{
    use Dispatchable;

    public function __construct(
        public readonly QrSlot $slot,
    ) {
    }

    public function handle(QRCodeService $qrCodeService): void
    {
        $qrCodeService->generateAllFormats($this->slot);

        Log::info("QR code generated for slot [{$this->slot->short_code}].");
    }

}

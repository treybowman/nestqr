<?php

namespace App\Jobs;

use App\Models\ListingPhoto;
use App\Services\ImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessListingPhotosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    /**
     * @param  int  $listingId
     * @param  array<int, string>  $tempFilePaths  Array of temporary file paths to process.
     */
    public function __construct(
        public readonly int $listingId,
        public readonly array $tempFilePaths,
    ) {
        $this->onQueue('image-processing');
    }

    public function handle(ImageService $imageService): void
    {
        $sortOrder = ListingPhoto::where('listing_id', $this->listingId)->max('sort_order') ?? 0;

        foreach ($this->tempFilePaths as $tempPath) {
            if (!file_exists($tempPath)) {
                Log::warning("Temp file not found for listing [{$this->listingId}]: {$tempPath}");
                continue;
            }

            try {
                $file = new UploadedFile(
                    path: $tempPath,
                    originalName: basename($tempPath),
                    test: true,
                );

                $paths = $imageService->processListingPhoto($file, $this->listingId);

                $sortOrder++;

                ListingPhoto::create([
                    'listing_id' => $this->listingId,
                    'file_path' => $paths['file_path'],
                    'thumbnail_path' => $paths['thumbnail_path'],
                    'sort_order' => $sortOrder,
                ]);

                // Clean up temp file
                @unlink($tempPath);
            } catch (\Throwable $e) {
                Log::error("Failed to process photo for listing [{$this->listingId}]: {$e->getMessage()}");
            }
        }

        Log::info("Processed photos for listing [{$this->listingId}].");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("ProcessListingPhotosJob failed for listing [{$this->listingId}]: {$exception->getMessage()}");
    }
}

<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function processListingPhoto(UploadedFile $file, int $listingId): array
    {
        $filename = uniqid('photo_') . '.' . $file->getClientOriginalExtension();
        $directory = "photos/listings/{$listingId}";

        // Save original (optimized)
        $image = $this->manager->read($file->getPathname());
        $image->scaleDown(width: 1920); // Max width 1920px
        $optimized = $image->toJpeg(85);

        $filePath = "{$directory}/{$filename}";
        Storage::disk('public')->put($filePath, (string) $optimized);

        // Create thumbnail
        $thumbnailWidth = config('nestqr.thumbnail_width', 400);
        $thumbnail = $this->manager->read($file->getPathname());
        $thumbnail->scaleDown(width: $thumbnailWidth);
        $thumbnailData = $thumbnail->toJpeg(80);

        $thumbFilename = 'thumb_' . $filename;
        $thumbPath = "{$directory}/{$thumbFilename}";
        Storage::disk('public')->put($thumbPath, (string) $thumbnailData);

        return [
            'file_path' => $filePath,
            'thumbnail_path' => $thumbPath,
        ];
    }

    public function processProfilePhoto(UploadedFile $file, int $userId): string
    {
        $filename = "profile_{$userId}." . $file->getClientOriginalExtension();
        $directory = "photos/profiles";

        $image = $this->manager->read($file->getPathname());
        $image->cover(400, 400);
        $optimized = $image->toJpeg(85);

        $path = "{$directory}/{$filename}";
        Storage::disk('public')->put($path, (string) $optimized);

        return $path;
    }

    public function processLogo(UploadedFile $file, int $userId): string
    {
        $filename = "logo_{$userId}." . $file->getClientOriginalExtension();
        $directory = "logos";

        $image = $this->manager->read($file->getPathname());
        $image->scaleDown(width: 500);

        $ext = strtolower($file->getClientOriginalExtension());
        if ($ext === 'png') {
            $data = $image->toPng();
        } else {
            $data = $image->toJpeg(90);
        }

        $path = "{$directory}/{$filename}";
        Storage::disk('public')->put($path, (string) $data);

        return $path;
    }

    public function deleteFile(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }

    public function deleteDirectory(string $path): bool
    {
        return Storage::disk('public')->deleteDirectory($path);
    }
}

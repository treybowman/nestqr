<?php

namespace App\Services;

use App\Models\QrSlot;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    public function generateAllFormats(QrSlot $slot): void
    {
        $url = $this->getRedirectUrl($slot);

        $this->generatePng($slot, $url, 'web');
        $this->generatePng($slot, $url, 'print');
        $this->generateSvg($slot, $url);
        $this->generatePdf($slot);

        $basePath = "qr-codes/{$slot->short_code}";
        $slot->update(['qr_image_path' => "{$basePath}/web.png"]);
    }

    public function generatePng(QrSlot $slot, string $url, string $quality = 'web'): string
    {
        $size = config("nestqr.qr_sizes.{$quality}", 600);

        $qrCode = new QrCode(
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: $size,
            margin: 20,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255),
        );

        $writer = new PngWriter();

        // Brand logo in center (20% of QR size) - optional
        $logo = null;
        $brandLogoPath = storage_path('app/public/' . config('nestqr.logo_path'));
        if (file_exists($brandLogoPath)) {
            $logoSize = (int) ($size * config('nestqr.brand_logo_ratio'));
            $logo = new Logo(
                path: $brandLogoPath,
                resizeToWidth: $logoSize,
                resizeToHeight: $logoSize,
                punchoutBackground: true,
            );
        }

        $result = $writer->write($qrCode, $logo);

        // Save the QR code
        $basePath = "qr-codes/{$slot->short_code}";
        $filename = "{$quality}.png";
        Storage::disk('public')->put("{$basePath}/{$filename}", $result->getString());

        // Overlay the emoji icon in bottom-right corner
        if ($slot->icon && $slot->icon->emoji) {
            $this->overlayEmojiIcon($slot, $basePath, $filename, $size);
        }

        return "{$basePath}/{$filename}";
    }

    public function generateSvg(QrSlot $slot, string $url): string
    {
        $qrCode = new QrCode(
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 600,
            margin: 20,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        $writer = new SvgWriter();
        $result = $writer->write($qrCode);

        $basePath = "qr-codes/{$slot->short_code}";
        Storage::disk('public')->put("{$basePath}/vector.svg", $result->getString());

        return "{$basePath}/vector.svg";
    }

    /**
     * Overlay the emoji icon on the QR code using Twemoji images.
     * Downloads the emoji PNG from Twemoji CDN and caches it locally.
     */
    protected function overlayEmojiIcon(QrSlot $slot, string $basePath, string $filename, int $size): void
    {
        $qrPath = storage_path("app/public/{$basePath}/{$filename}");
        if (!file_exists($qrPath)) {
            return;
        }

        try {
            // Get or download the emoji image
            $emojiImagePath = $this->getEmojiImage($slot->icon->emoji);
            if (!$emojiImagePath) {
                return;
            }

            $badgeSize = (int) ($size * 0.18);
            $iconSize = (int) ($badgeSize * 0.70);
            $padding = (int) ($size * 0.015);

            // Load QR code
            $qrImage = imagecreatefrompng($qrPath);
            imagealphablending($qrImage, true);
            $qrWidth = imagesx($qrImage);
            $qrHeight = imagesy($qrImage);

            // Create badge (white circle with emoji)
            $badge = imagecreatetruecolor($badgeSize, $badgeSize);
            imagesavealpha($badge, true);
            imagealphablending($badge, true);

            // Fill transparent
            $transparent = imagecolorallocatealpha($badge, 0, 0, 0, 127);
            imagefill($badge, 0, 0, $transparent);

            // Draw white circle background with slight shadow effect
            $shadow = imagecolorallocatealpha($badge, 0, 0, 0, 110);
            $center = (int) ($badgeSize / 2);
            imagefilledellipse($badge, $center + 1, $center + 1, $badgeSize - 2, $badgeSize - 2, $shadow);

            $white = imagecolorallocate($badge, 255, 255, 255);
            imagefilledellipse($badge, $center, $center, $badgeSize - 2, $badgeSize - 2, $white);

            // Load emoji image and resize
            $emojiImage = $this->loadImage($emojiImagePath);
            if ($emojiImage) {
                $emojiWidth = imagesx($emojiImage);
                $emojiHeight = imagesy($emojiImage);

                // Resize emoji to fit badge
                $resized = imagecreatetruecolor($iconSize, $iconSize);
                imagesavealpha($resized, true);
                imagealphablending($resized, true);
                $trans = imagecolorallocatealpha($resized, 0, 0, 0, 127);
                imagefill($resized, 0, 0, $trans);
                imagecopyresampled($resized, $emojiImage, 0, 0, 0, 0, $iconSize, $iconSize, $emojiWidth, $emojiHeight);

                // Place emoji centered in badge
                $emojiX = ($badgeSize - $iconSize) / 2;
                $emojiY = ($badgeSize - $iconSize) / 2;
                imagecopy($badge, $resized, (int) $emojiX, (int) $emojiY, 0, 0, $iconSize, $iconSize);

                imagedestroy($resized);
                imagedestroy($emojiImage);
            }

            // Position badge in bottom-right of QR code
            $x = $qrWidth - $badgeSize - $padding;
            $y = $qrHeight - $badgeSize - $padding;

            // Composite badge onto QR code
            imagecopy($qrImage, $badge, $x, $y, 0, 0, $badgeSize, $badgeSize);

            imagepng($qrImage, $qrPath);
            imagedestroy($qrImage);
            imagedestroy($badge);

        } catch (\Exception $e) {
            \Log::warning("Failed to overlay emoji on QR code: " . $e->getMessage());
        }
    }

    /**
     * Get the local path to an emoji image, downloading from Twemoji CDN if needed.
     */
    protected function getEmojiImage(string $emoji): ?string
    {
        $codepoint = $this->emojiToCodepoint($emoji);
        if (!$codepoint) {
            return null;
        }

        // Check local cache
        $cachePath = storage_path("app/emoji-cache/{$codepoint}.png");
        if (file_exists($cachePath)) {
            return $cachePath;
        }

        // Download from Twemoji CDN
        $url = "https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/72x72/{$codepoint}.png";

        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 10,
                    'user_agent' => 'NestQR/1.0',
                ],
            ]);

            $imageData = @file_get_contents($url, false, $context);

            if ($imageData === false) {
                \Log::warning("Failed to download Twemoji for codepoint: {$codepoint}");
                return null;
            }

            // Ensure cache directory exists
            $cacheDir = dirname($cachePath);
            if (!is_dir($cacheDir)) {
                mkdir($cacheDir, 0755, true);
            }

            file_put_contents($cachePath, $imageData);

            return $cachePath;

        } catch (\Exception $e) {
            \Log::warning("Twemoji download failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Convert an emoji character to its hex codepoint(s) for Twemoji URL.
     * Handles multi-codepoint emojis (e.g. flags, skin tones) with dashes.
     * Strips variation selectors (fe0f) for simpler emoji.
     */
    protected function emojiToCodepoint(string $emoji): ?string
    {
        if (empty($emoji)) {
            return null;
        }

        $codepoints = [];
        $length = mb_strlen($emoji, 'UTF-8');

        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($emoji, $i, 1, 'UTF-8');
            $ord = mb_ord($char, 'UTF-8');

            // Skip variation selectors and zero-width joiners for simple emoji
            if ($ord === 0xFE0F) {
                continue;
            }

            $codepoints[] = strtolower(dechex($ord));
        }

        if (empty($codepoints)) {
            return null;
        }

        // Try with all codepoints joined by dash
        $full = implode('-', $codepoints);

        return $full;
    }

    /**
     * Load an image from path, supporting PNG, JPEG, GIF, WEBP.
     */
    protected function loadImage(string $path): ?\GdImage
    {
        $info = @getimagesize($path);
        if (!$info) {
            return null;
        }

        return match ($info[2]) {
            IMAGETYPE_PNG => @imagecreatefrompng($path),
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_GIF => @imagecreatefromgif($path),
            IMAGETYPE_WEBP => @imagecreatefromwebp($path),
            default => null,
        };
    }

    public function getRedirectUrl(QrSlot $slot): string
    {
        $domain = config('nestqr.primary_domain', 'nestqr.com');
        return "https://{$domain}/{$slot->short_code}";
    }

    public function generatePdf(QrSlot $slot): string
    {
        $basePath = "qr-codes/{$slot->short_code}";
        $qrImagePath = storage_path("app/public/{$basePath}/print.png");

        if (!file_exists($qrImagePath)) {
            $this->generatePng($slot, $this->getRedirectUrl($slot), 'print');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.qr-code', [
            'slot' => $slot,
            'qrImageDataUri' => 'data:image/png;base64,' . base64_encode(file_get_contents($qrImagePath)),
            'shortUrl' => $this->getRedirectUrl($slot),
            'address' => $slot->currentListing?->address,
        ]);

        $pdfPath = "{$basePath}/qr-code.pdf";
        Storage::disk('public')->put($pdfPath, $pdf->output());

        return $pdfPath;
    }
}

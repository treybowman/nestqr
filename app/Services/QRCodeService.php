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

        // Overlay the agent's icon in bottom-right if assigned and SVG exists
        if ($slot->icon && $slot->icon->svg_path && $slot->icon->svg_path !== '') {
            $this->overlayIcon($slot, $basePath, $filename, $size);
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

    protected function overlayIcon(QrSlot $slot, string $basePath, string $filename, int $size): void
    {
        $iconPath = storage_path('app/public/icons/' . $slot->icon->svg_path);
        if (!file_exists($iconPath)) {
            return;
        }

        $qrPath = storage_path("app/public/{$basePath}/{$filename}");
        $iconSize = (int) ($size * config('nestqr.icon_ratio'));

        try {
            $manager = new \Intervention\Image\ImageManager(
                new \Intervention\Image\Drivers\Gd\Driver()
            );

            $qrImage = $manager->read($qrPath);
            $iconImage = $manager->read($iconPath)->resize($iconSize, $iconSize);

            // Position in bottom-right with small padding
            $padding = (int) ($size * 0.02);
            $x = $size - $iconSize - $padding;
            $y = $size - $iconSize - $padding;

            // Add white background circle behind icon
            $qrImage->place($iconImage, 'top-left', $x, $y);
            $qrImage->save($qrPath);
        } catch (\Exception $e) {
            \Log::warning("Failed to overlay icon on QR code: " . $e->getMessage());
        }
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
            'qrImageBase64' => base64_encode(file_get_contents($qrImagePath)),
            'url' => $this->getRedirectUrl($slot),
        ]);

        $pdfPath = "{$basePath}/qr-code.pdf";
        Storage::disk('public')->put($pdfPath, $pdf->output());

        return $pdfPath;
    }
}

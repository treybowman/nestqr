<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateLogo extends Command
{
    protected $signature = 'nestqr:generate-logo';

    protected $description = 'Generate the NestQR logo PNG for QR code centers';

    public function handle(): int
    {
        $size = 200;
        $img = imagecreatetruecolor($size, $size);
        imagesavealpha($img, true);

        // Transparent background
        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $transparent);

        // White circle background
        $white = imagecolorallocate($img, 255, 255, 255);
        $center = (int) ($size / 2);
        imagefilledellipse($img, $center, $center, $size - 4, $size - 4, $white);

        // Primary purple circle (inner)
        $purple = imagecolorallocate($img, 142, 99, 245); // #8e63f5
        $innerSize = $size - 24;
        imagefilledellipse($img, $center, $center, $innerSize, $innerSize, $purple);

        // Draw "N" letter in white
        $whiteText = imagecolorallocate($img, 255, 255, 255);
        $fontSize = 72;

        // Try to find a system font
        $fontPath = null;
        $candidates = [
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf',
            '/usr/share/fonts/truetype/freefont/FreeSansBold.ttf',
            '/usr/share/fonts/dejavu-sans-fonts/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/TTF/DejaVuSans-Bold.ttf',
            '/System/Library/Fonts/Helvetica.ttc',
        ];

        foreach ($candidates as $path) {
            if (file_exists($path)) {
                $fontPath = $path;
                break;
            }
        }

        if ($fontPath) {
            $bbox = imagettfbbox($fontSize, 0, $fontPath, 'N');
            $textWidth = abs($bbox[2] - $bbox[0]);
            $textHeight = abs($bbox[7] - $bbox[1]);
            $textX = ($size - $textWidth) / 2 - $bbox[0];
            $textY = ($size + $textHeight) / 2 - $bbox[7] - ($textHeight * 0.1);
            imagettftext($img, $fontSize, 0, (int) $textX, (int) $textY, $whiteText, $fontPath, 'N');
        } else {
            // Fallback: use built-in font scaled
            $fontWidth = imagefontwidth(5);
            $fontHeight = imagefontheight(5);
            $textX = ($size - $fontWidth) / 2;
            $textY = ($size - $fontHeight) / 2;
            imagestring($img, 5, (int) $textX, (int) $textY, 'N', $whiteText);
        }

        // Save to storage
        $dir = storage_path('app/public/logos');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir . '/nestqr-logo.png';
        imagepng($img, $path);
        imagedestroy($img);

        $this->info("Logo generated at: {$path}");

        return self::SUCCESS;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\QrSlot;
use App\Services\QRCodeService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QrSlotController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the user's QR slots.
     * The Livewire component handles data loading and interactions.
     */
    public function index()
    {
        return view('qr-slots.index');
    }

    /**
     * Display a specific QR slot with its icon and current listing.
     */
    public function show(QrSlot $qrSlot)
    {
        $this->authorize('view', $qrSlot);

        $qrSlot->load(['icon', 'currentListing']);

        return view('qr-slots.show', [
            'slot' => $qrSlot,
        ]);
    }

    /**
     * Download QR code in the specified format.
     *
     * Supported formats: web-png, print-png, svg, pdf
     */
    public function download(Request $request, QrSlot $qrSlot, string $format, QRCodeService $qrCodeService)
    {
        $this->authorize('view', $qrSlot);

        $allowedFormats = ['web-png', 'print-png', 'svg', 'pdf'];

        if (! in_array($format, $allowedFormats)) {
            abort(422, 'Invalid download format. Allowed formats: ' . implode(', ', $allowedFormats));
        }

        $publicUrl = $qrSlot->getPublicUrl();
        $filename = 'qr-' . $qrSlot->short_code;

        return match ($format) {
            'web-png' => $this->downloadPng($qrCodeService, $publicUrl, $filename, 400),
            'print-png' => $this->downloadPng($qrCodeService, $publicUrl, $filename . '-print', 1200),
            'svg' => $this->downloadSvg($qrCodeService, $publicUrl, $filename),
            'pdf' => $this->downloadPdf($qrCodeService, $qrSlot, $filename),
        };
    }

    /**
     * Generate and return a PNG download response.
     */
    private function downloadPng(QRCodeService $qrCodeService, string $url, string $filename, int $size): StreamedResponse
    {
        $imageData = $qrCodeService->generatePng($url, $size);

        return response()->streamDownload(function () use ($imageData) {
            echo $imageData;
        }, $filename . '.png', [
            'Content-Type' => 'image/png',
        ]);
    }

    /**
     * Generate and return an SVG download response.
     */
    private function downloadSvg(QRCodeService $qrCodeService, string $url, string $filename): StreamedResponse
    {
        $svgData = $qrCodeService->generateSvg($url);

        return response()->streamDownload(function () use ($svgData) {
            echo $svgData;
        }, $filename . '.svg', [
            'Content-Type' => 'image/svg+xml',
        ]);
    }

    /**
     * Generate and return a PDF download response.
     */
    private function downloadPdf(QRCodeService $qrCodeService, QrSlot $qrSlot, string $filename): StreamedResponse
    {
        $pdfData = $qrCodeService->generatePdf($qrSlot);

        return response()->streamDownload(function () use ($pdfData) {
            echo $pdfData;
        }, $filename . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}

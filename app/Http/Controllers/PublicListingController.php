<?php

namespace App\Http\Controllers;

use App\Models\QrSlot;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class PublicListingController extends Controller
{
    /**
     * Handle QR code scan redirect.
     *
     * This is the primary entry point when someone scans a NestQR code.
     * It records the scan, then either shows the public listing page
     * or redirects to the assignment page if no listing is linked.
     */
    public function redirect(Request $request, string $shortCode, AnalyticsService $analytics)
    {
        $slot = QrSlot::where('short_code', $shortCode)->firstOrFail();

        // Record the scan
        $analytics->recordScan($slot, [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
        ]);

        // If unassigned, show assignment page (requires login)
        if (! $slot->isAssigned()) {
            return redirect()->route('qr-slots.assign', $slot);
        }

        // Show the public listing page
        $listing = $slot->currentListing()->with(['photos', 'user'])->first();

        if (! $listing || $listing->status !== 'active') {
            return redirect()->route('qr-slots.assign', $slot);
        }

        return view('public.listing', [
            'listing' => $listing,
            'agent' => $listing->user,
            'slot' => $slot,
        ]);
    }

    /**
     * Show the assignment page for linking a listing to a QR slot.
     *
     * Only accessible by the authenticated owner of the QR slot.
     */
    public function assign(Request $request, QrSlot $qrSlot)
    {
        // Must be logged in and own this QR slot
        if (! $request->user() || $request->user()->id !== $qrSlot->user_id) {
            return redirect()->route('login')->with('redirect_after', route('qr-slots.assign', $qrSlot));
        }

        $listings = $request->user()->listings()
            ->where('status', 'active')
            ->whereNull('qr_slot_id')
            ->orWhere('qr_slot_id', $qrSlot->id)
            ->get();

        return view('qr-slots.assign', [
            'slot' => $qrSlot,
            'listings' => $listings,
        ]);
    }
}

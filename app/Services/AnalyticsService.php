<?php

namespace App\Services;

use App\Models\QrSlot;
use App\Models\ScanAnalytic;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function recordScan(QrSlot $slot, array $data): ScanAnalytic
    {
        $scan = ScanAnalytic::create([
            'qr_slot_id' => $slot->id,
            'listing_id' => $slot->current_listing_id,
            'scanned_at' => now(),
            'ip_address' => $data['ip_address'] ?? null,
            'user_agent' => $data['user_agent'] ?? null,
            'referrer' => $data['referrer'] ?? null,
        ]);

        $slot->increment('total_scans');

        return $scan;
    }

    public function getDashboardStats(User $user, int $days = 30): array
    {
        $slotIds = $user->qrSlots()->pluck('id');
        $startDate = now()->subDays($days);

        return [
            'total_scans' => ScanAnalytic::whereIn('qr_slot_id', $slotIds)
                ->where('scanned_at', '>=', $startDate)
                ->count(),
            'total_qr_codes' => $user->qrSlots()->count(),
            'active_listings' => $user->listings()->where('status', 'active')->count(),
            'assigned_qr_codes' => $user->qrSlots()->whereNotNull('current_listing_id')->count(),
            'max_qr_codes' => $user->maxQrSlots(),
        ];
    }

    public function getScansOverTime(User $user, int $days = 30): Collection
    {
        $slotIds = $user->qrSlots()->pluck('id');
        $startDate = now()->subDays($days);

        return ScanAnalytic::whereIn('qr_slot_id', $slotIds)
            ->where('scanned_at', '>=', $startDate)
            ->select(DB::raw('DATE(scanned_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getTopQrCodes(User $user, int $days = 30, int $limit = 10): Collection
    {
        $startDate = now()->subDays($days);

        return QrSlot::where('user_id', $user->id)
            ->withCount(['scanAnalytics' => function ($query) use ($startDate) {
                $query->where('scanned_at', '>=', $startDate);
            }])
            ->with('currentListing')
            ->orderByDesc('scan_analytics_count')
            ->limit($limit)
            ->get();
    }

    public function getRecentScans(User $user, int $limit = 20): Collection
    {
        $slotIds = $user->qrSlots()->pluck('id');

        return ScanAnalytic::whereIn('qr_slot_id', $slotIds)
            ->with(['qrSlot', 'listing'])
            ->latest('scanned_at')
            ->limit($limit)
            ->get();
    }

    public function exportToCsv(User $user, int $days = 30): string
    {
        $slotIds = $user->qrSlots()->pluck('id');
        $startDate = now()->subDays($days);

        $scans = ScanAnalytic::whereIn('qr_slot_id', $slotIds)
            ->where('scanned_at', '>=', $startDate)
            ->with(['qrSlot', 'listing'])
            ->orderBy('scanned_at', 'desc')
            ->get();

        $csv = "Date,QR Code,Short Code,Listing,Referrer\n";
        foreach ($scans as $scan) {
            $csv .= implode(',', [
                $scan->scanned_at->format('Y-m-d H:i:s'),
                '"' . ($scan->qrSlot->short_code ?? 'N/A') . '"',
                '"' . ($scan->qrSlot->short_code ?? '') . '"',
                '"' . ($scan->listing->address ?? 'Unassigned') . '"',
                '"' . ($scan->referrer ?? 'Direct') . '"',
            ]) . "\n";
        }

        return $csv;
    }

    public function anonymizeOldIps(int $days = null): int
    {
        $days = $days ?? config('nestqr.ip_anonymize_days', 30);
        $cutoff = now()->subDays($days);

        return ScanAnalytic::where('scanned_at', '<', $cutoff)
            ->whereNotNull('ip_address')
            ->where('ip_address', 'NOT LIKE', '%.***')
            ->update(['ip_address' => DB::raw("CONCAT(SUBSTRING_INDEX(ip_address, '.', 2), '.***.*****')")]);
    }

    // Platform-wide stats for admin
    public function getPlatformStats(): array
    {
        return [
            'total_users' => \App\Models\User::count(),
            'total_scans' => ScanAnalytic::count(),
            'total_qr_codes' => QrSlot::count(),
            'total_listings' => \App\Models\Listing::count(),
            'scans_today' => ScanAnalytic::whereDate('scanned_at', today())->count(),
            'new_users_this_week' => \App\Models\User::where('created_at', '>=', now()->subWeek())->count(),
        ];
    }
}

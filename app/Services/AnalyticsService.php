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

        $csv = "Date,Short Code,URL,Listing,Referrer\n";
        foreach ($scans as $scan) {
            $csv .= implode(',', [
                $scan->scanned_at->format('Y-m-d H:i:s'),
                '"' . ($scan->qrSlot->short_code ?? 'N/A') . '"',
                '"' . ($scan->qrSlot?->getPublicUrl() ?? '') . '"',
                '"' . str_replace('"', '""', $scan->listing->address ?? 'Unassigned') . '"',
                '"' . str_replace('"', '""', $scan->referrer ?? 'Direct') . '"',
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
        $plans = config('nestqr.plans');

        $usersByPlan = \App\Models\User::query()
            ->selectRaw('plan_tier, COUNT(*) as count')
            ->groupBy('plan_tier')
            ->pluck('count', 'plan_tier')
            ->toArray();

        $activeSubsByPlan = DB::table('subscriptions')
            ->where('stripe_status', 'active')
            ->join('users', 'subscriptions.user_id', '=', 'users.id')
            ->selectRaw('users.plan_tier, COUNT(*) as count')
            ->groupBy('users.plan_tier')
            ->pluck('count', 'plan_tier')
            ->toArray();

        $mrr = 0;
        foreach ($activeSubsByPlan as $tier => $count) {
            $mrr += ($plans[$tier]['price'] ?? 0) * $count;
        }

        $canceledThisMonth = DB::table('subscriptions')
            ->where('stripe_status', 'canceled')
            ->whereMonth('ends_at', now()->month)
            ->whereYear('ends_at', now()->year)
            ->count();

        $newUsersThisMonth = \App\Models\User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Signup trend: signups per day for the last 30 days
        $signupTrend = \App\Models\User::where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Fill in zeros for missing days
        $trendData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $trendData[$date] = $signupTrend[$date] ?? 0;
        }

        return [
            'total_users'         => \App\Models\User::count(),
            'total_scans'         => ScanAnalytic::count(),
            'total_qr_slots'      => QrSlot::count(),
            'total_listings'      => \App\Models\Listing::count(),
            'scans_today'         => ScanAnalytic::whereDate('scanned_at', today())->count(),
            'new_users_this_week' => \App\Models\User::where('created_at', '>=', now()->subWeek())->count(),
            'new_users_this_month' => $newUsersThisMonth,
            'users_by_plan'       => $usersByPlan,
            'active_subscriptions' => array_sum($activeSubsByPlan),
            'mrr'                 => $mrr,
            'canceled_this_month' => $canceledThisMonth,
            'signup_trend'        => $trendData,
        ];
    }
}

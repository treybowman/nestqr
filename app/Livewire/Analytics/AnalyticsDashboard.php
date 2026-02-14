<?php

namespace App\Livewire\Analytics;

use App\Services\AnalyticsService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Analytics')]
class AnalyticsDashboard extends Component
{
    public int $days = 30;
    public array $stats = [];
    public array $scansOverTime = [];
    public array $topQrCodes = [];
    public array $recentScans = [];

    public function mount(): void
    {
        $this->loadData();
    }

    public function updatedDays(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $analytics = app(AnalyticsService::class);

        $this->stats = $analytics->getDashboardStats($user, $this->days);

        $this->scansOverTime = $analytics->getScansOverTime($user, $this->days)
            ->map(fn ($item) => [
                'date' => $item->date,
                'count' => $item->count,
            ])
            ->toArray();

        $this->topQrCodes = $analytics->getTopQrCodes($user, $this->days)
            ->map(fn ($slot) => [
                'id' => $slot->id,
                'short_code' => $slot->short_code,
                'scans' => $slot->scan_analytics_count,
                'listing_address' => $slot->currentListing?->address ?? 'Unassigned',
            ])
            ->toArray();

        $this->recentScans = $analytics->getRecentScans($user)
            ->map(fn ($scan) => [
                'id' => $scan->id,
                'scanned_at' => $scan->scanned_at->diffForHumans(),
                'scanned_at_full' => $scan->scanned_at->format('M j, Y g:i A'),
                'short_code' => $scan->qrSlot?->short_code ?? 'N/A',
                'listing_address' => $scan->listing?->address ?? 'Unassigned',
                'referrer' => $scan->referrer ?? 'Direct',
            ])
            ->toArray();
    }

    public function export(): StreamedResponse
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        abort_unless($user->hasAdvancedAnalytics(), 403, 'Analytics export is available on Pro plans and above.');

        $analytics = app(AnalyticsService::class);
        $csv = $analytics->exportToCsv($user, $this->days);

        $filename = 'nestqr-analytics-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function render()
    {
        return view('livewire.analytics.analytics-dashboard');
    }
}

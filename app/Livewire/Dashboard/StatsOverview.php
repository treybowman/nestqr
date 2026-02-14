<?php

namespace App\Livewire\Dashboard;

use App\Services\AnalyticsService;
use Livewire\Component;

class StatsOverview extends Component
{
    public array $stats = [];

    public function mount(AnalyticsService $analytics): void
    {
        $this->stats = $analytics->getDashboardStats(auth()->user());
    }

    public function render()
    {
        return view('livewire.dashboard.stats-overview');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with platform-wide statistics.
     */
    public function __invoke(AnalyticsService $analytics)
    {
        return view('admin.dashboard', [
            'stats' => $analytics->getPlatformStats(),
        ]);
    }
}

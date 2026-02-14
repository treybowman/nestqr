<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, AnalyticsService $analytics)
    {
        $stats = $analytics->getDashboardStats($request->user());

        return view('dashboard.index', [
            'stats' => $stats,
        ]);
    }
}

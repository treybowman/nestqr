<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     * The Livewire component handles charts, filters, and real-time data.
     */
    public function index()
    {
        return view('analytics.index');
    }

    /**
     * Export analytics data as a CSV download.
     */
    public function export(Request $request, AnalyticsService $analytics): StreamedResponse
    {
        $validated = $request->validate([
            'days' => 'required|integer|min:1|max:365',
        ]);

        $days = (int) $validated['days'];
        $user = $request->user();
        $csv = $analytics->exportToCsv($user, $days);
        $filename = 'nestqr-analytics-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}

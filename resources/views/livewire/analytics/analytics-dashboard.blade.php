<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Analytics</h2>
        <div class="flex items-center space-x-2">
            <!-- Date Range Buttons -->
            <div class="inline-flex rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-0.5">
                <button wire:click="$set('days', 7)" class="px-3 py-1.5 text-xs font-medium rounded-md transition {{ $days === 7 ? 'bg-primary-500 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">7 Days</button>
                <button wire:click="$set('days', 30)" class="px-3 py-1.5 text-xs font-medium rounded-md transition {{ $days === 30 ? 'bg-primary-500 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">30 Days</button>
                <button wire:click="$set('days', 90)" class="px-3 py-1.5 text-xs font-medium rounded-md transition {{ $days === 90 ? 'bg-primary-500 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">90 Days</button>
            </div>

            @if(auth()->user()->hasAdvancedAnalytics())
                <button wire:click="export" class="btn-secondary text-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export CSV
                </button>
            @endif
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Scans</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($stats['total_scans'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </div>
            </div>
        </div>
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Unique QR Codes Scanned</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $stats['unique_qr_scanned'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                </div>
            </div>
        </div>
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Top QR Code</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1 uppercase tracking-wider font-mono">{{ $topQrCodes[0]['short_code'] ?? '--' }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Scans Over Time Chart -->
    <div class="card p-6 mb-6">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider mb-4">Scans Over Time</h3>
        <div
            wire:ignore
            x-data="{
                chart: null,
                initChart() {
                    const ctx = this.$refs.canvas.getContext('2d');
                    const data = @js($scansOverTime);
                    const isDark = document.documentElement.classList.contains('dark');

                    this.chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.map(d => d.date),
                            datasets: [{
                                label: 'Scans',
                                data: data.map(d => d.count),
                                borderColor: '#8e63f5',
                                backgroundColor: 'rgba(142, 99, 245, 0.1)',
                                fill: true,
                                tension: 0.4,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#8e63f5',
                                borderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: isDark ? '#1f2937' : '#fff',
                                    titleColor: isDark ? '#f3f4f6' : '#111827',
                                    bodyColor: isDark ? '#d1d5db' : '#4b5563',
                                    borderColor: isDark ? '#374151' : '#e5e7eb',
                                    borderWidth: 1,
                                    cornerRadius: 8,
                                    padding: 12,
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { color: isDark ? '#6b7280' : '#9ca3af', maxTicksLimit: 10 }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: { color: isDark ? '#1f2937' : '#f3f4f6' },
                                    ticks: { color: isDark ? '#6b7280' : '#9ca3af', precision: 0 }
                                }
                            }
                        }
                    });
                }
            }"
            x-init="initChart()"
            class="h-72"
        >
            <canvas x-ref="canvas"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Performing QR Codes -->
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider">Top Performing QR Codes</h3>
            </div>
            @if(count($topQrCodes) > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($topQrCodes as $index => $qr)
                        <div class="px-6 py-3 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-500 dark:text-gray-400">{{ $index + 1 }}</span>
                                <div>
                                    <p class="text-sm font-mono font-semibold text-gray-900 dark:text-gray-100 uppercase">{{ $qr['short_code'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px]">{{ $qr['listing_address'] }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-primary-600 dark:text-primary-400">{{ number_format($qr['scans']) }} scans</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-8 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">No scan data yet for this period.</p>
                </div>
            @endif
        </div>

        <!-- Recent Scan Activity -->
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider">Recent Scan Activity</h3>
            </div>
            @if(count($recentScans) > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($recentScans as $scan)
                        <div class="px-6 py-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-900 dark:text-gray-100">
                                            <span class="font-mono font-semibold uppercase">{{ $scan['short_code'] }}</span>
                                            <span class="text-gray-500 dark:text-gray-400">scanned</span>
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $scan['listing_address'] }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500 dark:text-gray-400" title="{{ $scan['scanned_at_full'] }}">{{ $scan['scanned_at'] }}</p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500">{{ $scan['referrer'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-8 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">No recent scan activity.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@section('title', 'Admin Dashboard')

<x-layouts.app>
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Admin Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Platform overview and management.</p>
        </div>

        <!-- Top Stats Row -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="card p-5">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Users</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($stats['total_users'] ?? 0) }}</p>
                <p class="text-xs text-green-600 dark:text-green-400 mt-1">+{{ $stats['new_users_this_month'] ?? 0 }} this month</p>
            </div>
            <div class="card p-5">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total QR Codes</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($stats['total_qr_slots'] ?? 0) }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ number_format($stats['total_listings'] ?? 0) }} listings</p>
            </div>
            <div class="card p-5">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Scans</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($stats['total_scans'] ?? 0) }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ number_format($stats['scans_today'] ?? 0) }} today</p>
            </div>
            <div class="card p-5">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Est. MRR</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">${{ number_format(($stats['mrr'] ?? 0) / 100) }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $stats['active_subscriptions'] ?? 0 }} active subs</p>
            </div>
        </div>

        <!-- Revenue + Plan Breakdown Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Users by Plan -->
            <div class="card p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider mb-4">Users by Plan</h3>
                <div class="grid grid-cols-2 gap-3">
                    @php
                        $planColors = [
                            'free'      => 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300',
                            'pro'       => 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300',
                            'unlimited' => 'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300',
                            'company'   => 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300',
                        ];
                    @endphp
                    @foreach(['free', 'pro', 'unlimited', 'company'] as $tier)
                        <div class="rounded-xl p-4 {{ $planColors[$tier] }}">
                            <p class="text-2xl font-bold">{{ number_format($stats['users_by_plan'][$tier] ?? 0) }}</p>
                            <p class="text-xs font-medium capitalize mt-0.5">{{ $tier }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Subscription Health -->
            <div class="card p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider mb-4">Subscription Health</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Active subscriptions</span>
                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">{{ $stats['active_subscriptions'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Canceled this month</span>
                        <span class="text-sm font-semibold text-red-600 dark:text-red-400">{{ $stats['canceled_this_month'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">New users this week</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $stats['new_users_this_week'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Estimated MRR</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-gray-100">${{ number_format(($stats['mrr'] ?? 0) / 100) }}/mo</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signup Trend Chart -->
        <div class="card p-6 mb-6">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider mb-4">New Signups — Last 30 Days</h3>
            <div class="relative h-40">
                <canvas id="signupChart"></canvas>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.users.index') }}" class="card p-5 hover:shadow-lg transition group flex items-center space-x-4">
                <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition flex-shrink-0">
                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <p class="font-semibold text-sm text-gray-900 dark:text-gray-100">Manage Users</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $stats['total_users'] ?? 0 }} total</p>
                </div>
            </a>
            <a href="{{ route('admin.domains.index') }}" class="card p-5 hover:shadow-lg transition group flex items-center space-x-4">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                </div>
                <div>
                    <p class="font-semibold text-sm text-gray-900 dark:text-gray-100">Manage Domains</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Configure markets</p>
                </div>
            </a>
            <a href="{{ route('admin.companies.index') }}" class="card p-5 hover:shadow-lg transition group flex items-center space-x-4">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition flex-shrink-0">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <p class="font-semibold text-sm text-gray-900 dark:text-gray-100">Companies</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Manage company accounts</p>
                </div>
            </a>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <script>
    (function () {
        const labels = @json(array_keys($stats['signup_trend'] ?? []));
        const data   = @json(array_values($stats['signup_trend'] ?? []));
        const isDark = document.documentElement.classList.contains('dark');

        const ctx = document.getElementById('signupChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    data,
                    backgroundColor: isDark ? 'rgba(99,102,241,0.5)' : 'rgba(99,102,241,0.3)',
                    borderColor: 'rgba(99,102,241,1)',
                    borderWidth: 1,
                    borderRadius: 3,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        ticks: {
                            color: isDark ? '#9ca3af' : '#6b7280',
                            maxTicksLimit: 10,
                        },
                        grid: { display: false },
                    },
                    y: {
                        ticks: {
                            color: isDark ? '#9ca3af' : '#6b7280',
                            stepSize: 1,
                        },
                        grid: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' },
                        beginAtZero: true,
                    },
                },
            },
        });
    })();
    </script>
    @endpush
</x-layouts.app>

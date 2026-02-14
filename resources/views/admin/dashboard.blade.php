@section('title', 'Admin Dashboard')

<x-layouts.app>
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Admin Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Platform overview and management.</p>
        </div>

        <!-- Platform Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($stats['total_users'] ?? 0) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total QR Codes</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($stats['total_qr_slots'] ?? 0) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Listings</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($stats['total_listings'] ?? 0) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                </div>
            </div>

            <div class="card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Scans</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ number_format($stats['total_scans'] ?? 0) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plan Breakdown -->
        <div class="card p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Users by Plan</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach(['free', 'pro', 'unlimited', 'company'] as $tier)
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['users_by_plan'][$tier] ?? 0 }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 capitalize mt-1">{{ $tier }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('admin.users.index') }}" class="card p-6 hover:shadow-lg transition group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">Manage Users</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">View, edit, and manage all user accounts.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.domains.index') }}" class="card p-6 hover:shadow-lg transition group">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">Manage Domains</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Configure active domains and markets.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-layouts.app>

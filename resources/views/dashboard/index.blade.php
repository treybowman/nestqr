<x-layouts.app>
    <x-slot:title>Dashboard</x-slot:title>

    <div class="space-y-6">
        <livewire:dashboard.stats-overview />

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('qr-slots.index') }}" class="card p-6 hover:shadow-md transition group">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center group-hover:bg-primary-200 dark:group-hover:bg-primary-800/40 transition">
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Manage QR Codes</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Create & assign QR codes</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('listings.create') }}" class="card p-6 hover:shadow-md transition group">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center group-hover:bg-green-200 dark:group-hover:bg-green-800/40 transition">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Add Listing</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Create a new property</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('analytics.index') }}" class="card p-6 hover:shadow-md transition group">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-800/40 transition">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">View Analytics</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Track scan performance</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-layouts.app>

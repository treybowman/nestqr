<x-app-layout>
    <x-slot:title>QR Code - {{ $slot->short_code }}</x-slot:title>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Back Link -->
        <a href="{{ route('qr-slots.index') }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to QR Codes
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: QR Code Preview -->
            <div class="lg:col-span-1">
                <div class="card p-6 text-center">
                    @if($slot->qr_image_path)
                        <div class="bg-white rounded-xl p-4 inline-block shadow-sm border border-gray-100">
                            <img src="{{ asset('storage/' . $slot->qr_image_path) }}" alt="QR Code {{ $slot->short_code }}" class="w-56 h-56 object-contain mx-auto">
                        </div>
                    @else
                        <div class="w-56 h-56 mx-auto bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-500 mx-auto animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-sm text-gray-400 mt-2">Generating...</p>
                            </div>
                        </div>
                    @endif

                    <!-- Short Code -->
                    <div class="mt-4">
                        <p class="text-2xl font-mono font-bold text-gray-900 dark:text-gray-100 uppercase tracking-widest">{{ $slot->short_code }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 break-all">{{ $slot->getPublicUrl() }}</p>
                    </div>

                    <!-- Download Buttons -->
                    @if($slot->qr_image_path)
                        <div class="mt-6 grid grid-cols-2 gap-2">
                            <a href="{{ asset('storage/qr-codes/' . $slot->short_code . '/web.png') }}" download class="btn-secondary text-xs justify-center py-2">PNG Web</a>
                            <a href="{{ asset('storage/qr-codes/' . $slot->short_code . '/print.png') }}" download class="btn-secondary text-xs justify-center py-2">PNG Print</a>
                            <a href="{{ asset('storage/qr-codes/' . $slot->short_code . '/vector.svg') }}" download class="btn-secondary text-xs justify-center py-2">SVG</a>
                            <a href="{{ asset('storage/qr-codes/' . $slot->short_code . '/print.pdf') }}" download class="btn-secondary text-xs justify-center py-2">PDF</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Icon Info -->
                <div class="card p-6">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider mb-4">QR Code Details</h3>
                    <dl class="space-y-4">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Icon</dt>
                            <dd class="flex items-center space-x-2">
                                @if($slot->icon)
                                    <span class="text-xl">{{ $slot->icon->emoji }}</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $slot->icon->name }}</span>
                                @else
                                    <span class="text-sm text-gray-400">No icon</span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Created</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $slot->created_at->format('M j, Y') }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Total Scans</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($slot->total_scans) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Status</dt>
                            <dd>
                                @if($slot->isAssigned())
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Assigned</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">Unassigned</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Assigned Listing -->
                <div class="card p-6">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider mb-4">Assigned Listing</h3>
                    @if($slot->isAssigned() && $slot->currentListing)
                        <div class="flex items-start space-x-4">
                            @if($slot->currentListing->primaryPhoto)
                                <img src="{{ $slot->currentListing->primaryPhoto->thumbnail_url }}" alt="" class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                            @else
                                <div class="w-20 h-20 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-8 h-8 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $slot->currentListing->address }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $slot->currentListing->city }}, {{ $slot->currentListing->state }} {{ $slot->currentListing->zip }}</p>
                                <p class="text-lg font-bold text-primary-600 dark:text-primary-400 mt-1">{{ $slot->currentListing->formatted_price }}</p>
                                <a href="{{ route('listings.show', $slot->currentListing) }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 mt-2 inline-flex items-center">
                                    View Listing
                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('qr-slots.assign', $slot) }}" class="btn-secondary text-sm">Reassign Listing</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">No listing is currently assigned to this QR code.</p>
                            <a href="{{ route('qr-slots.assign', $slot) }}" class="btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                Assign Listing
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Scan Statistics -->
                <div class="card p-6">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider mb-4">Scan Statistics</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-900 rounded-xl">
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($slot->total_scans) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total Scans</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-900 rounded-xl">
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $slot->scanAnalytics()->where('scanned_at', '>=', now()->subDays(7))->count() }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Last 7 Days</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-900 rounded-xl">
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $slot->scanAnalytics()->where('scanned_at', '>=', now()->subDays(30))->count() }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Last 30 Days</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

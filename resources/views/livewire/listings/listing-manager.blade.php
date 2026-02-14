<div>
    <!-- Flash Messages -->
    @if(session('message'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 rounded-lg text-sm flex items-center" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Listings</h2>
        <a href="{{ route('listings.create') }}" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Add Listing
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="card p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- Search -->
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search by address, city, state, or zip..."
                    class="input-field pl-10"
                >
            </div>

            <!-- Status Filter -->
            <select wire:model.live="statusFilter" class="input-field w-full sm:w-40">
                <option value="all">All Status</option>
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="sold">Sold</option>
                <option value="inactive">Inactive</option>
            </select>

            <!-- Sort -->
            <select wire:model.live="sortBy" class="input-field w-full sm:w-40">
                <option value="created_at">Newest</option>
                <option value="address">Address</option>
                <option value="city">City</option>
                <option value="price">Price</option>
                <option value="status">Status</option>
            </select>
        </div>
    </div>

    <!-- Listings Table / Cards -->
    @if($listings->count() > 0)
        <!-- Desktop Table (hidden on mobile) -->
        <div class="card overflow-hidden hidden md:block">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">QR Code</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($listings as $listing)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition" wire:key="listing-{{ $listing->id }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    @if($listing->primaryPhoto)
                                        <img src="{{ $listing->primaryPhoto->thumbnail_url }}" alt="" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $listing->address }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $listing->city }}, {{ $listing->state }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $listing->formatted_price }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    @if($listing->beds) {{ $listing->beds }} bd @endif
                                    @if($listing->baths) / {{ $listing->baths }} ba @endif
                                    @if($listing->sqft) / {{ number_format($listing->sqft) }} sqft @endif
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium
                                    {{ $listing->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                    {{ $listing->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                    {{ $listing->status === 'sold' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                    {{ $listing->status === 'inactive' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' : '' }}
                                ">{{ ucfirst($listing->status) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($listing->qr_slot_id && $listing->qrSlot)
                                    <span class="inline-flex items-center text-xs text-green-600 dark:text-green-400">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                        {{ strtoupper($listing->qrSlot->short_code) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500">None</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('listings.show', $listing) }}" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('listings.edit', $listing) }}" class="p-1.5 text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <button wire:click="deleteListing({{ $listing->id }})" wire:confirm="Are you sure you want to delete this listing? This action cannot be undone." class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards (shown on mobile only) -->
        <div class="md:hidden space-y-3">
            @foreach($listings as $listing)
                <div class="card p-4" wire:key="listing-mobile-{{ $listing->id }}">
                    <div class="flex items-start space-x-3">
                        @if($listing->primaryPhoto)
                            <img src="{{ $listing->primaryPhoto->thumbnail_url }}" alt="" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                        @else
                            <div class="w-16 h-16 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $listing->address }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $listing->city }}, {{ $listing->state }}</p>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium ml-2 flex-shrink-0
                                    {{ $listing->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                    {{ $listing->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                    {{ $listing->status === 'sold' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                    {{ $listing->status === 'inactive' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' : '' }}
                                ">{{ ucfirst($listing->status) }}</span>
                            </div>
                            <p class="text-sm font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $listing->formatted_price }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                @if($listing->beds) {{ $listing->beds }} bd @endif
                                @if($listing->baths) / {{ $listing->baths }} ba @endif
                                @if($listing->sqft) / {{ number_format($listing->sqft) }} sqft @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center justify-end space-x-3 mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('listings.show', $listing) }}" class="text-xs text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">View</a>
                        <a href="{{ route('listings.edit', $listing) }}" class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400">Edit</a>
                        <button wire:click="deleteListing({{ $listing->id }})" wire:confirm="Are you sure you want to delete this listing?" class="text-xs text-red-500 hover:text-red-700">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $listings->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="card p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto">
                <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-gray-100">No listings found</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                @if($search || $statusFilter !== 'all')
                    No listings match your current filters. Try adjusting your search criteria.
                @else
                    Get started by creating your first property listing.
                @endif
            </p>
            @if(!$search && $statusFilter === 'all')
                <a href="{{ route('listings.create') }}" class="btn-primary mt-6 inline-flex">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Your First Listing
                </a>
            @endif
        </div>
    @endif
</div>

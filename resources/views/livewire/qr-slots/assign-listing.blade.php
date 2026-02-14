<div class="max-w-2xl mx-auto">
    <!-- QR Code Preview -->
    <div class="card p-6 text-center mb-6">
        <div class="flex items-center justify-center space-x-4">
            @if($slot->qr_image_path)
                <img src="{{ asset('storage/' . $slot->qr_image_path) }}" alt="QR Code" class="w-24 h-24 object-contain">
            @else
                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                </div>
            @endif
            <div class="text-left">
                <p class="text-lg font-mono font-bold text-gray-900 dark:text-gray-100 uppercase tracking-wider">{{ $slot->short_code }}</p>
                @if($slot->icon)
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $slot->icon->emoji }} {{ $slot->icon->name }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Select Listing -->
    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Select a listing to assign</h2>

    @error('selectedListingId')
        <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg text-sm text-red-700 dark:text-red-300">{{ $message }}</div>
    @enderror

    @if($availableListings->count() > 0)
        <div class="space-y-3 mb-6">
            @foreach($availableListings as $listing)
                <button
                    wire:click="$set('selectedListingId', {{ $listing->id }})"
                    class="w-full text-left card p-4 transition border-2 hover:shadow-md
                        {{ $selectedListingId === $listing->id ? 'border-primary-500 ring-2 ring-primary-500/20' : 'border-transparent' }}"
                >
                    <div class="flex items-center space-x-4">
                        @if($listing->primaryPhoto)
                            <img src="{{ $listing->primaryPhoto->thumbnail_url }}" alt="" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                        @else
                            <div class="w-16 h-16 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $listing->address }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $listing->city }}, {{ $listing->state }} {{ $listing->zip }}</p>
                            <div class="flex items-center space-x-3 mt-1">
                                <span class="text-sm font-bold text-primary-600 dark:text-primary-400">{{ $listing->formatted_price }}</span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium
                                    {{ $listing->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                    {{ $listing->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                    {{ $listing->status === 'sold' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                    {{ $listing->status === 'inactive' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400' : '' }}
                                ">{{ ucfirst($listing->status) }}</span>
                            </div>
                        </div>
                        @if($selectedListingId === $listing->id)
                            <div class="w-6 h-6 bg-primary-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        @endif
                    </div>
                </button>
            @endforeach
        </div>

        <!-- Confirm Button -->
        <div class="flex items-center justify-between">
            <a href="{{ route('qr-slots.index') }}" class="btn-secondary">Cancel</a>
            <button wire:click="assign" wire:loading.attr="disabled" class="btn-primary" {{ !$selectedListingId ? 'disabled' : '' }}>
                <svg wire:loading wire:target="assign" class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                Assign Listing
            </button>
        </div>
    @else
        <!-- Empty State -->
        <div class="card p-10 text-center">
            <div class="w-14 h-14 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto">
                <svg class="w-7 h-7 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h3 class="mt-4 text-base font-semibold text-gray-900 dark:text-gray-100">No available listings</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">You need to create an active listing before you can assign it to a QR code.</p>
            <a href="{{ route('listings.create') }}" class="btn-primary mt-5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Create Listing
            </a>
        </div>
    @endif
</div>

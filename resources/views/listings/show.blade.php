<x-layouts.app>
    <x-slot:title>{{ $listing->address }}</x-slot:title>

    <div class="max-w-4xl mx-auto">
        <!-- Back Link -->
        <a href="{{ route('listings.index') }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition mb-6">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Listings
        </a>

        <!-- Photo Gallery -->
        @if($listing->photos->count() > 0)
            <div x-data="{ activeSlide: 0 }" class="card overflow-hidden mb-6">
                <div class="relative aspect-[16/9] bg-gray-100 dark:bg-gray-800">
                    @foreach($listing->photos as $index => $photo)
                        <img
                            x-show="activeSlide === {{ $index }}"
                            src="{{ $photo->url }}"
                            alt="Photo {{ $index + 1 }}"
                            class="w-full h-full object-cover"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                        >
                    @endforeach
                    @if($listing->photos->count() > 1)
                        <button @click="activeSlide = (activeSlide - 1 + {{ $listing->photos->count() }}) % {{ $listing->photos->count() }}" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/50 text-white rounded-full flex items-center justify-center hover:bg-black/70 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button @click="activeSlide = (activeSlide + 1) % {{ $listing->photos->count() }}" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/50 text-white rounded-full flex items-center justify-center hover:bg-black/70 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 bg-black/50 text-white text-xs px-3 py-1 rounded-full">
                            <span x-text="activeSlide + 1"></span> / {{ $listing->photos->count() }}
                        </div>
                    @endif
                </div>

                <!-- Thumbnails -->
                @if($listing->photos->count() > 1)
                    <div class="flex space-x-2 p-3 overflow-x-auto">
                        @foreach($listing->photos as $index => $photo)
                            <button
                                @click="activeSlide = {{ $index }}"
                                class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition"
                                :class="activeSlide === {{ $index }} ? 'border-primary-500' : 'border-transparent opacity-60 hover:opacity-100'"
                            >
                                <img src="{{ $photo->thumbnail_url }}" alt="" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Property Details -->
            <div class="lg:col-span-2 space-y-6">
                <div class="card p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $listing->formatted_price }}</h2>
                            <p class="text-lg text-gray-600 dark:text-gray-400 mt-1">{{ $listing->address }}</p>
                            <p class="text-gray-500 dark:text-gray-500">{{ $listing->city }}, {{ $listing->state }} {{ $listing->zip }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium
                            {{ $listing->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                            {{ $listing->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                            {{ $listing->status === 'sold' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                            {{ $listing->status === 'inactive' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' : '' }}
                        ">{{ ucfirst($listing->status) }}</span>
                    </div>

                    <!-- Property Stats -->
                    <div class="flex items-center space-x-6 py-4 border-t border-b border-gray-200 dark:border-gray-700">
                        @if($listing->beds)
                            <div class="text-center">
                                <p class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $listing->beds }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Beds</p>
                            </div>
                        @endif
                        @if($listing->baths)
                            <div class="text-center">
                                <p class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $listing->baths }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Baths</p>
                            </div>
                        @endif
                        @if($listing->sqft)
                            <div class="text-center">
                                <p class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($listing->sqft) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Sq Ft</p>
                            </div>
                        @endif
                    </div>

                    <!-- Description -->
                    @if($listing->description)
                        <div class="mt-4">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider mb-2">Description</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed whitespace-pre-line">{{ $listing->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- QR Code Assignment -->
                <div class="card p-6">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider mb-4">QR Code</h3>
                    @if($listing->qr_slot_id && $listing->qrSlot)
                        <div class="text-center">
                            @if($listing->qrSlot->qr_image_path)
                                <img src="{{ asset('storage/' . $listing->qrSlot->qr_image_path) }}" alt="QR Code" class="w-32 h-32 object-contain mx-auto">
                            @endif
                            <p class="text-sm font-mono font-bold text-gray-900 dark:text-gray-100 mt-2 uppercase tracking-wider">{{ $listing->qrSlot->short_code }}</p>
                            <a href="{{ route('qr-slots.show', $listing->qrSlot) }}" class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400 mt-1 inline-block">View QR Details</a>
                        </div>
                    @else
                        <div class="text-center py-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">No QR code assigned</p>
                            <a href="{{ route('qr-slots.index') }}" class="btn-secondary text-sm">Assign QR Code</a>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="card p-6">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider mb-4">Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('listings.edit', $listing) }}" class="btn-primary w-full justify-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit Listing
                        </a>
                        <form method="POST" action="{{ route('listings.destroy', $listing) }}" onsubmit="return confirm('Are you sure you want to delete this listing? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger w-full justify-center text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Delete Listing
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="card p-6">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider mb-3">Info</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Created</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $listing->created_at->format('M j, Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Updated</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $listing->updated_at->format('M j, Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Photos</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $listing->photos->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

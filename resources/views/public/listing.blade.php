@section('title', $listing->full_address ?? 'Property Listing')

<x-layouts.public :listing="$listing">
    <div class="max-w-lg mx-auto pb-20">
        <!-- Photo Gallery -->
        @if($listing->photos->count() > 0)
            <div x-data="{ current: 0, total: {{ $listing->photos->count() }}, lightbox: false, lightboxSrc: '' }" class="relative">
                <!-- Main Image -->
                <div class="relative aspect-[4/3] bg-gray-100 dark:bg-gray-800 overflow-hidden">
                    @foreach($listing->photos as $index => $photo)
                        <img
                            x-show="current === {{ $index }}"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            src="{{ $photo->url }}"
                            alt="{{ $listing->address }} photo {{ $index + 1 }}"
                            class="absolute inset-0 w-full h-full object-cover cursor-pointer"
                            @click="lightboxSrc = '{{ $photo->url }}'; lightbox = true"
                        >
                    @endforeach

                    <!-- Navigation Arrows -->
                    @if($listing->photos->count() > 1)
                        <button @click="current = current === 0 ? total - 1 : current - 1" class="absolute left-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/40 hover:bg-black/60 rounded-full flex items-center justify-center text-white transition backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button @click="current = current === total - 1 ? 0 : current + 1" class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/40 hover:bg-black/60 rounded-full flex items-center justify-center text-white transition backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>

                        <!-- Counter -->
                        <div class="absolute bottom-3 right-3 bg-black/50 backdrop-blur-sm text-white text-xs font-medium px-2.5 py-1 rounded-full">
                            <span x-text="current + 1"></span> / {{ $listing->photos->count() }}
                        </div>
                    @endif
                </div>

                <!-- Thumbnails -->
                @if($listing->photos->count() > 1)
                    <div class="flex gap-1.5 mt-1.5 overflow-x-auto pb-1 px-1">
                        @foreach($listing->photos as $index => $photo)
                            <button
                                @click="current = {{ $index }}"
                                class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden transition"
                                :class="current === {{ $index }} ? 'ring-2 ring-primary-500 opacity-100' : 'opacity-60 hover:opacity-80'"
                            >
                                <img src="{{ $photo->thumbnail_url }}" alt="" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Lightbox -->
                <div x-show="lightbox" x-transition.opacity @click="lightbox = false" class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4 cursor-pointer" style="display: none;">
                    <button @click="lightbox = false" class="absolute top-4 right-4 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <img :src="lightboxSrc" alt="" class="max-w-full max-h-full object-contain rounded-lg" @click.stop>
                </div>
            </div>
        @else
            <div class="aspect-[4/3] bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">No photos available</p>
                </div>
            </div>
        @endif

        <!-- Property Info -->
        <div class="px-4 mt-5">
            <!-- Price -->
            @if($listing->price)
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $listing->formatted_price }}</div>
            @endif

            <!-- Address -->
            <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $listing->address }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $listing->city }}, {{ $listing->state }} {{ $listing->zip }}</p>

            <!-- Status Badge -->
            @if($listing->status !== 'active')
                <span class="inline-flex items-center mt-2 px-2.5 py-1 rounded-full text-xs font-semibold
                    {{ $listing->status === 'sold' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}
                    {{ $listing->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                    {{ $listing->status === 'inactive' ? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' : '' }}
                ">
                    {{ ucfirst($listing->status) }}
                </span>
            @endif

            <!-- Quick Stats -->
            <div class="grid grid-cols-3 gap-4 mt-5 py-4 border-y border-gray-200 dark:border-gray-700">
                @if($listing->beds)
                    <div class="text-center">
                        <p class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $listing->beds }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Beds</p>
                    </div>
                @endif
                @if($listing->baths)
                    <div class="text-center">
                        <p class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $listing->baths }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Baths</p>
                    </div>
                @endif
                @if($listing->sqft)
                    <div class="text-center">
                        <p class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($listing->sqft) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sq Ft</p>
                    </div>
                @endif
            </div>

            <!-- Description -->
            @if($listing->description)
                <div class="mt-5">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2">About This Property</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed whitespace-pre-line">{{ $listing->description }}</p>
                </div>
            @endif

            <!-- Agent Info -->
            @if(isset($agent))
                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        @if($agent->photo_path)
                            <img src="{{ Storage::url($agent->photo_path) }}" alt="{{ $agent->name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                                <span class="text-lg font-bold text-primary-600 dark:text-primary-400">{{ strtoupper(substr($agent->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $agent->name }}</p>
                            @if($agent->phone)
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $agent->phone }}</p>
                            @endif
                        </div>
                    </div>

                    @if($agent->bio)
                        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400 line-clamp-3">{{ $agent->bio }}</p>
                    @endif

                    <!-- Contact Buttons -->
                    <div class="grid grid-cols-2 gap-2 mt-4">
                        @if($agent->phone)
                            <a href="tel:{{ $agent->phone }}" class="btn-primary text-center text-sm py-2.5">
                                <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                Call
                            </a>
                        @endif
                        <a href="mailto:{{ $agent->email }}" class="btn-secondary text-center text-sm py-2.5">
                            <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Email
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.public>

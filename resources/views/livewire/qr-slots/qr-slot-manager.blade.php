<div>
    <!-- Flash Messages -->
    @if(session('message'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 rounded-lg text-sm flex items-center" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('message') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 rounded-lg text-sm flex items-center" x-data="{ show: true }" x-show="show" x-transition>
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center space-x-3">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">QR Codes</h2>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400">
                {{ $slots->total() }}/{{ auth()->user()->maxQrSlots() }} used
            </span>
        </div>
        @if(auth()->user()->canCreateQrSlot())
            <button wire:click="$set('showCreateModal', true)" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Create QR Code
            </button>
        @else
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-500 dark:text-gray-400">Slot limit reached</span>
                <a href="{{ route('settings.subscription') }}" class="btn-primary">Upgrade Plan</a>
            </div>
        @endif
    </div>

    <!-- QR Code Cards Grid -->
    @if($slots->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($slots as $slot)
                <div class="card overflow-hidden" wire:key="slot-{{ $slot->id }}">
                    <!-- QR Code Preview -->
                    <div class="bg-gray-50 dark:bg-gray-900 p-6 flex items-center justify-center border-b border-gray-200 dark:border-gray-700">
                        @if($slot->qr_image_path)
                            <img src="{{ asset('storage/' . $slot->qr_image_path) }}" alt="QR Code {{ $slot->short_code }}" class="w-40 h-40 object-contain">
                        @else
                            <div class="w-40 h-40 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Generating...</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Card Body -->
                    <div class="p-5">
                        <!-- Short Code & Icon -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <span class="text-lg font-mono font-bold text-gray-900 dark:text-gray-100 uppercase tracking-wider">{{ $slot->short_code }}</span>
                            </div>
                            @if($slot->icon)
                                <div class="flex items-center space-x-1.5 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="text-lg">{{ $slot->icon->emoji }}</span>
                                    <span>{{ $slot->icon->name }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Assigned Listing -->
                        <div class="mb-4">
                            @if($slot->isAssigned() && $slot->currentListing)
                                <div class="flex items-center space-x-2 text-sm">
                                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                    <span class="text-gray-700 dark:text-gray-300 truncate">{{ $slot->currentListing->address }}</span>
                                </div>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                    Unassigned
                                </span>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between border-t border-gray-100 dark:border-gray-700 pt-4 -mx-5 px-5">
                            <!-- Download Dropdown -->
                            @if($slot->qr_image_path)
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        Download
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 bottom-full mb-2 w-44 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-10">
                                        <a href="{{ asset('storage/qr-codes/' . $slot->short_code . '/web.png') }}" download class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">PNG (Web)</a>
                                        <a href="{{ asset('storage/qr-codes/' . $slot->short_code . '/print.png') }}" download class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">PNG (Print)</a>
                                        <a href="{{ asset('storage/qr-codes/' . $slot->short_code . '/vector.svg') }}" download class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">SVG</a>
                                        <a href="{{ asset('storage/qr-codes/' . $slot->short_code . '/print.pdf') }}" download class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">PDF</a>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-gray-400">Generating...</span>
                            @endif

                            <div class="flex items-center space-x-2">
                                <!-- Assign / Reassign -->
                                <a href="{{ route('qr-slots.assign', $slot) }}" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                    {{ $slot->isAssigned() ? 'Reassign' : 'Assign' }}
                                </a>

                                <!-- Delete -->
                                <button wire:click="deleteSlot({{ $slot->id }})" wire:confirm="Are you sure you want to delete this QR code? This action cannot be undone." class="inline-flex items-center text-sm text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $slots->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="card p-12 text-center">
            <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center mx-auto">
                <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-gray-100">No QR codes yet</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">Create your first QR code to start linking properties and tracking scans.</p>
            <button wire:click="$set('showCreateModal', true)" class="btn-primary mt-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Create Your First QR Code
            </button>
        </div>
    @endif

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data @keydown.escape.window="$wire.$set('showCreateModal', false)">
            <div class="flex items-end sm:items-center justify-center min-h-full p-4">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 transition-opacity" wire:click="$set('showCreateModal', false)"></div>

                <!-- Modal Panel -->
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg transform transition-all border border-gray-200 dark:border-gray-700">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Create QR Code</h3>
                        <button wire:click="$set('showCreateModal', false)" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-4" x-data="{ search: '', activeCategory: 'all' }">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Choose an icon to identify your QR code.</p>

                        @error('selectedIconId')
                            <div class="mb-3 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg text-sm text-red-700 dark:text-red-300">{{ $message }}</div>
                        @enderror

                        <!-- Search -->
                        <div class="relative mb-3">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <input type="text" x-model="search" placeholder="Search icons..." class="input-field pl-9 py-2 text-sm">
                        </div>

                        <!-- Category Tabs -->
                        <div class="flex flex-wrap gap-1.5 mb-3">
                            @php
                                $categories = ['all' => 'All', 'real-estate' => 'Property', 'amenities' => 'Amenities', 'nature' => 'Nature', 'lifestyle' => 'Lifestyle', 'marketing' => 'Marketing', 'seasonal' => 'Seasonal', 'general' => 'General'];
                            @endphp
                            @foreach($categories as $key => $label)
                                <button
                                    @click="activeCategory = '{{ $key }}'"
                                    :class="activeCategory === '{{ $key }}' ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                    class="px-2.5 py-1 rounded-full text-[11px] font-medium transition"
                                >{{ $label }}</button>
                            @endforeach
                        </div>

                        <!-- Icon Grid -->
                        <div class="grid grid-cols-5 sm:grid-cols-6 gap-2 max-h-72 overflow-y-auto pr-1">
                            @foreach($this->availableIcons as $icon)
                                @php
                                    $canAccess = auth()->user()->canAccessIcon($icon);
                                @endphp
                                <button
                                    wire:click="$set('selectedIconId', {{ $icon->id }})"
                                    @if(!$canAccess) disabled @endif
                                    x-show="(activeCategory === 'all' || activeCategory === '{{ $icon->category }}') && (search === '' || '{{ strtolower($icon->name) }}'.includes(search.toLowerCase()))"
                                    class="relative flex flex-col items-center p-2 rounded-xl border-2 transition
                                        {{ $selectedIconId === $icon->id ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}
                                        {{ !$canAccess ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}"
                                >
                                    <span class="text-xl">{{ $icon->emoji }}</span>
                                    <span class="text-[9px] text-gray-500 dark:text-gray-400 mt-0.5 truncate w-full text-center leading-tight">{{ $icon->name }}</span>
                                    @if($icon->tier === 'pro' && !$canAccess)
                                        <span class="absolute -top-1 -right-1 bg-primary-500 text-white text-[7px] font-bold px-1 py-0.5 rounded-full">PRO</span>
                                    @endif
                                    @if($selectedIconId === $icon->id)
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-primary-500 rounded-full flex items-center justify-center">
                                            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-end space-x-3 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        <button wire:click="$set('showCreateModal', false)" class="btn-secondary">Cancel</button>
                        <button wire:click="createSlot" wire:loading.attr="disabled" class="btn-primary">
                            <svg wire:loading wire:target="createSlot" class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Create QR Code
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

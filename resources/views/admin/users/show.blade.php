@section('title', $user->name)

<x-layouts.app>
    <div class="max-w-4xl mx-auto">
        <!-- Back Link -->
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition mb-6">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Users
        </a>

        <!-- User Profile Card -->
        <div class="card p-6 mb-6">
            <div class="flex items-start space-x-4">
                @if($user->photo_path)
                    <img src="{{ Storage::url($user->photo_path) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover">
                @else
                    <div class="w-16 h-16 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                        <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                @endif
                <div class="flex-1">
                    <div class="flex items-center space-x-2">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h2>
                        @if($user->is_admin)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">ADMIN</span>
                        @endif
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 capitalize">{{ $user->plan_tier }}</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $user->email }}</p>
                    @if($user->phone)
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->phone }}</p>
                    @endif
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">Joined {{ $user->created_at->format('M j, Y') }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary text-sm">Edit</a>
                    @if(!$user->is_admin)
                        <a href="{{ route('admin.users.impersonate', $user) }}" class="btn-primary text-sm" onclick="return confirm('Impersonate {{ $user->name }}?')">Impersonate</a>
                    @endif
                </div>
            </div>
            @if($user->bio)
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700 pt-4">{{ $user->bio }}</p>
            @endif
        </div>

        <!-- QR Codes -->
        <div class="card p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">QR Codes ({{ $user->qrSlots->count() }})</h3>
            @if($user->qrSlots->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($user->qrSlots as $slot)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-3 text-center">
                            @if($slot->qr_image_path)
                                <img src="{{ Storage::url($slot->qr_image_path) }}" alt="{{ $slot->short_code }}" class="w-full aspect-square object-contain mb-2">
                            @else
                                <div class="w-full aspect-square bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center mb-2">
                                    <span class="text-gray-400">No QR</span>
                                </div>
                            @endif
                            <p class="text-xs font-mono font-semibold text-gray-900 dark:text-gray-100">{{ $slot->short_code }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ $slot->icon?->emoji }} {{ $slot->currentListing ? 'Assigned' : 'Unassigned' }}
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ number_format($slot->total_scans) }} scans</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No QR codes created.</p>
            @endif
        </div>

        <!-- Listings -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Listings ({{ $user->listings->count() }})</h3>
            @if($user->listings->count() > 0)
                <div class="space-y-3">
                    @foreach($user->listings as $listing)
                        <div class="flex items-center space-x-4 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                            @if($listing->primary_photo)
                                <img src="{{ $listing->primary_photo }}" alt="" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                            @else
                                <div class="w-16 h-16 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $listing->address }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $listing->city }}, {{ $listing->state }} {{ $listing->zip }}</p>
                                @if($listing->price)
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 mt-0.5">{{ $listing->formatted_price }}</p>
                                @endif
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium capitalize
                                {{ $listing->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                {{ $listing->status === 'sold' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                {{ $listing->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                {{ $listing->status === 'inactive' ? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' : '' }}
                            ">{{ $listing->status }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No listings created.</p>
            @endif
        </div>
    </div>
</x-layouts.app>

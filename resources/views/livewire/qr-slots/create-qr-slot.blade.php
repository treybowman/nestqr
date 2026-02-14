<div>
    <form wire:submit="create">
        <div class="space-y-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Select an icon for your new QR code.</p>

            @error('selectedIconId') <div class="p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg text-sm text-red-700 dark:text-red-300">{{ $message }}</div> @enderror

            @foreach($icons as $tier => $tierIcons)
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        {{ ucfirst($tier) }} Icons
                        @if($tier === 'pro')
                            <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">PRO</span>
                        @endif
                    </h4>
                    <div class="grid grid-cols-5 sm:grid-cols-8 gap-2">
                        @foreach($tierIcons as $icon)
                            @php
                                $canAccess = auth()->user()->canAccessIcon($icon);
                            @endphp
                            <button
                                type="button"
                                wire:click="$set('selectedIconId', {{ $icon->id }})"
                                @if(!$canAccess) disabled @endif
                                class="relative aspect-square rounded-xl border-2 flex items-center justify-center text-2xl transition
                                    {{ $selectedIconId === $icon->id ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20 ring-2 ring-primary-500/30' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}
                                    {{ !$canAccess ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer' }}"
                                title="{{ $icon->name }}{{ !$canAccess ? ' (Pro required)' : '' }}"
                            >
                                {{ $icon->emoji }}
                                @if(!$canAccess)
                                    <span class="absolute top-0.5 right-0.5 text-[8px]">🔒</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" wire:click="$dispatch('close-modal')" class="btn-secondary">Cancel</button>
            <button type="submit" wire:loading.attr="disabled" class="btn-primary">
                <svg wire:loading wire:target="create" class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                Create QR Code
            </button>
        </div>
    </form>
</div>

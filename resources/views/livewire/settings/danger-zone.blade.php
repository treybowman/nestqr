<div>
    <div class="card border-red-200 dark:border-red-900/50 p-6">
        <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-1">Danger Zone</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Permanently delete your account and all associated data. This action is irreversible.</p>

        @if(!$confirmDelete)
            <button wire:click="$set('confirmDelete', true)" class="btn-danger">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Delete My Account
            </button>
        @else
            <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-200 dark:border-red-800">
                <div class="flex items-start space-x-3 mb-4">
                    <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <h4 class="text-sm font-semibold text-red-800 dark:text-red-300">Are you absolutely sure?</h4>
                        <p class="text-sm text-red-700 dark:text-red-400 mt-1">This will permanently delete your account including:</p>
                        <ul class="text-sm text-red-700 dark:text-red-400 mt-2 space-y-1 list-disc list-inside">
                            <li>All QR codes and scan history</li>
                            <li>All property listings and photos</li>
                            <li>Your profile and branding settings</li>
                            <li>Subscription and billing data</li>
                        </ul>
                    </div>
                </div>

                <form wire:submit="deleteAccount">
                    <div class="mb-4">
                        <label for="deleteConfirmation" class="block text-sm font-medium text-red-800 dark:text-red-300 mb-1">
                            Type <span class="font-mono font-bold">DELETE</span> to confirm
                        </label>
                        <input
                            wire:model="deleteConfirmation"
                            type="text"
                            id="deleteConfirmation"
                            class="input-field font-mono border-red-300 dark:border-red-800 focus:border-red-500 focus:ring-red-500"
                            placeholder="DELETE"
                            autocomplete="off"
                        >
                        @error('deleteConfirmation') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center space-x-3">
                        <button type="submit" class="btn-danger" {{ $deleteConfirmation !== 'DELETE' ? 'disabled' : '' }}>
                            <svg wire:loading wire:target="deleteAccount" class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Permanently Delete Account
                        </button>
                        <button type="button" wire:click="$set('confirmDelete', false)" class="btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>

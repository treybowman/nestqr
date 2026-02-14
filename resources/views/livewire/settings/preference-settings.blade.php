<div x-data x-on:theme-changed.window="
    let theme = $event.detail.theme;
    if (theme === 'dark') {
        localStorage.setItem('darkMode', 'true');
        document.documentElement.classList.add('dark');
    } else if (theme === 'light') {
        localStorage.setItem('darkMode', 'false');
        document.documentElement.classList.remove('dark');
    } else {
        localStorage.removeItem('darkMode');
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
    if (Alpine.store('darkMode')) { Alpine.store('darkMode').on = document.documentElement.classList.contains('dark'); }
">
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Preferences</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Configure your display preferences and default domain.</p>

        @if(session('message'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 rounded-lg text-sm flex items-center" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit="save">
            <div class="space-y-6">
                <!-- Theme Preference -->
                <div>
                    <label class="label">Theme</label>
                    <div class="grid grid-cols-3 gap-3 mt-1">
                        <label class="relative cursor-pointer">
                            <input wire:model.live="themePreference" type="radio" value="light" class="sr-only peer">
                            <div class="p-4 rounded-xl border-2 text-center transition peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600">
                                <svg class="w-6 h-6 mx-auto text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 mt-2">Light</p>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input wire:model.live="themePreference" type="radio" value="dark" class="sr-only peer">
                            <div class="p-4 rounded-xl border-2 text-center transition peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600">
                                <svg class="w-6 h-6 mx-auto text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 mt-2">Dark</p>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input wire:model.live="themePreference" type="radio" value="system" class="sr-only peer">
                            <div class="p-4 rounded-xl border-2 text-center transition peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600">
                                <svg class="w-6 h-6 mx-auto text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 mt-2">System</p>
                            </div>
                        </label>
                    </div>
                    @error('themePreference') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Preferred Domain -->
                <div>
                    <label for="preferredDomain" class="label">Preferred Domain</label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Choose which domain your QR codes will redirect through.</p>
                    <select wire:model="preferredDomain" id="preferredDomain" class="input-field">
                        <option value="">Default ({{ config('nestqr.primary_domain', 'nestqr.com') }})</option>
                        @foreach($domains as $domain)
                            <option value="{{ $domain->domain }}">{{ $domain->domain }} ({{ $domain->market_name }})</option>
                        @endforeach
                    </select>
                    @error('preferredDomain') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" wire:loading.attr="disabled" class="btn-primary">
                    <svg wire:loading wire:target="save" class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Save Preferences
                </button>
            </div>
        </form>
    </div>
</div>

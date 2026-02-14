<div>
    <div class="card p-6">
        <div class="flex items-center justify-between mb-1">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Branding</h3>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 uppercase tracking-wider">Pro</span>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Customize how your public listing pages look to clients.</p>

        @if(session('message'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 rounded-lg text-sm flex items-center" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit="save">
            <div class="space-y-6">
                <!-- Logo Upload -->
                <div>
                    <label class="label">Custom Logo</label>
                    <div class="flex items-center space-x-4">
                        <div class="w-24 h-24 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center overflow-hidden bg-gray-50 dark:bg-gray-900">
                            @if($logo)
                                <img src="{{ $logo->temporaryUrl() }}" alt="Logo preview" class="w-full h-full object-contain p-2">
                            @elseif($currentLogo)
                                <img src="{{ $currentLogo }}" alt="Current logo" class="w-full h-full object-contain p-2">
                            @else
                                <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <div>
                            <label for="logo" class="btn-secondary text-sm cursor-pointer">
                                Upload Logo
                            </label>
                            <input wire:model="logo" type="file" id="logo" accept="image/png,image/jpeg,image/svg+xml" class="hidden">
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">PNG, JPG, or SVG. Max 2MB.</p>
                            @error('logo') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    @if($currentLogo)
                        <button type="button" wire:click="removeLogo" wire:confirm="Are you sure you want to remove your logo?" class="text-sm text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 mt-2">
                            Remove logo
                        </button>
                    @endif
                </div>

                <!-- Brand Color -->
                <div>
                    <label for="brandColor" class="label">Brand Color</label>
                    <div class="flex items-center space-x-3">
                        <input wire:model.live="brandColor" type="color" id="brandColor" class="w-12 h-10 rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer p-0.5">
                        <input wire:model.live="brandColor" type="text" class="input-field w-32 font-mono text-sm uppercase" placeholder="#8e63f5" maxlength="7">
                    </div>
                    @error('brandColor') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Preview -->
                <div>
                    <label class="label">Preview</label>
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 bg-gray-50 dark:bg-gray-900">
                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-200 dark:border-gray-700">
                            @if($logo)
                                <img src="{{ $logo->temporaryUrl() }}" alt="" class="w-8 h-8 object-contain">
                            @elseif($currentLogo)
                                <img src="{{ $currentLogo }}" alt="" class="w-8 h-8 object-contain">
                            @else
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: {{ $brandColor }}20;">
                                    <span class="text-sm font-bold" style="color: {{ $brandColor }};">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="mt-3">
                            <div class="w-full h-2 rounded-full" style="background-color: {{ $brandColor }};"></div>
                            <p class="text-xs text-gray-400 mt-2 text-center">Accent color applied to buttons and highlights</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" wire:loading.attr="disabled" class="btn-primary">
                    <svg wire:loading wire:target="save" class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Save Branding
                </button>
            </div>
        </form>
    </div>
</div>

<div>
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Profile</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Update your personal information and profile photo.</p>

        @if(session('message'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 rounded-lg text-sm flex items-center" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit="save">
            <!-- Photo Upload -->
            <div class="flex items-center space-x-6 mb-6">
                <div class="relative">
                    @if($photo)
                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="w-20 h-20 rounded-full object-cover border-2 border-primary-200 dark:border-primary-800">
                    @elseif($currentPhotoUrl)
                        <img src="{{ $currentPhotoUrl }}" alt="{{ $name }}" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                    @else
                        <div class="w-20 h-20 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center border-2 border-primary-200 dark:border-primary-800">
                            <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ substr($name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
                <div>
                    <label for="photo" class="btn-secondary text-sm cursor-pointer">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Change Photo
                    </label>
                    <input wire:model="photo" type="file" id="photo" accept="image/*" class="hidden">
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">JPG, PNG. Max 2MB.</p>
                    @error('photo') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="label">Full Name</label>
                    <input wire:model="name" type="text" id="name" class="input-field">
                    @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="label">Email Address</label>
                    <input wire:model="email" type="email" id="email" class="input-field">
                    @error('email') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="label">Phone Number</label>
                    <input wire:model="phone" type="tel" id="phone" class="input-field" placeholder="(555) 123-4567">
                    @error('phone') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="label">Bio</label>
                    <textarea wire:model="bio" id="bio" rows="3" class="input-field" placeholder="Tell clients a bit about yourself..."></textarea>
                    @error('bio') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" wire:loading.attr="disabled" class="btn-primary">
                    <svg wire:loading wire:target="save" class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Save Profile
                </button>
            </div>
        </form>

        <!-- Change Password -->
        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
            <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-1">Change Password</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Update your password to keep your account secure.</p>

            @if(session('password-message'))
                <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 rounded-lg text-sm flex items-center" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('password-message') }}
                </div>
            @endif

            <form wire:submit="updatePassword">
                <div class="space-y-4 max-w-md">
                    <div>
                        <label for="currentPassword" class="label">Current Password</label>
                        <input wire:model="currentPassword" type="password" id="currentPassword" class="input-field">
                        @error('currentPassword') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="newPassword" class="label">New Password</label>
                        <input wire:model="newPassword" type="password" id="newPassword" class="input-field">
                        @error('newPassword') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="newPasswordConfirmation" class="label">Confirm New Password</label>
                        <input wire:model="newPasswordConfirmation" type="password" id="newPasswordConfirmation" class="input-field">
                        @error('newPasswordConfirmation') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" wire:loading.attr="disabled" class="btn-secondary">
                        <svg wire:loading wire:target="updatePassword" class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

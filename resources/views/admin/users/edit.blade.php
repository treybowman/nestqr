@section('title', 'Edit User')

<x-layouts.app>
    <div class="max-w-2xl mx-auto">
        <!-- Back Link -->
        <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition mb-6">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to {{ $user->name }}
        </a>

        <div class="card p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Edit User: {{ $user->name }}</h2>

            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    <!-- Name -->
                    <div>
                        <label for="name" class="label">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="input-field">
                        @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="label">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="input-field">
                        @error('email') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="label">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="input-field">
                        @error('phone') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Plan Tier -->
                    <div>
                        <label for="plan_tier" class="label">Plan Tier</label>
                        <select name="plan_tier" id="plan_tier" class="input-field">
                            @foreach(['free', 'pro', 'unlimited', 'company'] as $plan)
                                <option value="{{ $plan }}" {{ old('plan_tier', $user->plan_tier) === $plan ? 'selected' : '' }}>{{ ucfirst($plan) }}</option>
                            @endforeach
                        </select>
                        @error('plan_tier') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Admin -->
                    <div class="flex items-center space-x-3">
                        <input type="hidden" name="is_admin" value="0">
                        <input type="checkbox" name="is_admin" id="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }} class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <label for="is_admin" class="text-sm font-medium text-gray-900 dark:text-gray-100">Administrator</label>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-between">
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Permanently delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger text-sm">Delete User</button>
                    </form>

                    <div class="flex space-x-3">
                        <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary">Cancel</a>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>

<div>
    <!-- Flash Message -->
    @if(session('message'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 rounded-lg text-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Search & Filters -->
    <div class="card p-4 mb-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search users by name or email..." class="input-field">
            </div>
            <select wire:model.live="planFilter" class="input-field sm:w-40">
                <option value="">All Plans</option>
                <option value="free">Free</option>
                <option value="pro">Pro</option>
                <option value="unlimited">Unlimited</option>
                <option value="company">Company</option>
            </select>
            <a href="{{ route('admin.users.export') }}" class="btn-secondary whitespace-nowrap">Export CSV</a>
        </div>
    </div>

    <!-- Bulk Action Bar -->
    @if(count($selected) > 0)
        <div class="mb-4 p-3 bg-primary-50 dark:bg-primary-900/30 border border-primary-200 dark:border-primary-800 rounded-xl flex flex-wrap items-center gap-3">
            <span class="text-sm font-medium text-primary-800 dark:text-primary-200">{{ count($selected) }} selected</span>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-xs text-primary-600 dark:text-primary-400">Change plan:</span>
                @foreach(['free', 'pro', 'unlimited', 'company'] as $plan)
                    <button wire:click="bulkChangePlan('{{ $plan }}')" wire:confirm="Change all selected users to {{ ucfirst($plan) }}?"
                            class="text-xs px-2.5 py-1 rounded-lg border border-primary-300 dark:border-primary-700 text-primary-700 dark:text-primary-300 hover:bg-primary-100 dark:hover:bg-primary-900/50">
                        {{ ucfirst($plan) }}
                    </button>
                @endforeach
                <button wire:click="bulkDelete" wire:confirm="Permanently delete all selected users? This cannot be undone."
                        class="text-xs px-2.5 py-1 rounded-lg border border-red-300 dark:border-red-700 text-red-700 dark:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30">
                    Delete Selected
                </button>
            </div>
            <button wire:click="$set('selected', [])" class="ml-auto text-xs text-primary-600 dark:text-primary-400 hover:underline">Clear</button>
        </div>
    @endif

    <!-- Users Table -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-4 py-3">
                            <input type="checkbox" wire:model.live="selectAll" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">QR Codes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Listings</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($this->users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition {{ in_array((string)$user->id, $selected) ? 'bg-primary-50/50 dark:bg-primary-900/10' : '' }}">
                            <td class="px-4 py-4">
                                @if(!$user->is_admin)
                                    <input type="checkbox" wire:model.live="selected" value="{{ $user->id }}"
                                           class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($user->photo_path)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($user->photo_path) }}" alt="">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                                                <span class="text-sm font-bold text-primary-600 dark:text-primary-400">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $user->name }}
                                            @if($user->is_admin)
                                                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">ADMIN</span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select wire:change="changePlan({{ $user->id }}, $event.target.value)" class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-lg py-1 px-2">
                                    @foreach(['free', 'pro', 'unlimited', 'company'] as $plan)
                                        <option value="{{ $plan }}" {{ $user->plan_tier === $plan ? 'selected' : '' }}>{{ ucfirst($plan) }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->qr_slots_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->listings_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->created_at->format('M j, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">View</a>
                                @if(!$user->is_admin)
                                    <form method="POST" action="{{ route('admin.users.impersonate', $user) }}" class="inline"
                                          onsubmit="return confirm('Impersonate {{ addslashes($user->name) }}?')">
                                        @csrf
                                        <button type="submit" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">Impersonate</button>
                                    </form>
                                    <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Are you sure you want to delete this user? This cannot be undone." class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                        Delete
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($this->users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $this->users->links() }}
            </div>
        @endif
    </div>
</div>

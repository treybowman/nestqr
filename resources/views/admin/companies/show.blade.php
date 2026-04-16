@section('title', $company->name)

<x-layouts.app>
    <div class="max-w-5xl mx-auto">
        <a href="{{ route('admin.companies.index') }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition mb-6">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Companies
        </a>

        <!-- Company Profile -->
        <div class="card p-6 mb-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-4">
                    @if($company->logo_url)
                        <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="w-16 h-16 rounded-xl object-contain border border-gray-200 dark:border-gray-700 p-1">
                    @else
                        <div class="w-16 h-16 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                            <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ strtoupper(substr($company->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $company->name }}</h2>
                        @if($company->billing_email)
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $company->billing_email }}</p>
                        @endif
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
                            Plan started {{ ($company->plan_start_date ?? $company->created_at)->format('M j, Y') }}
                        </p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.companies.destroy', $company) }}"
                      onsubmit="return confirm('Delete this company? Agents will not be deleted.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger text-sm">Delete Company</button>
                </form>
            </div>

            @if($company->admin)
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Account Owner</p>
                    <a href="{{ route('admin.users.show', $company->admin) }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline font-medium">
                        {{ $company->admin->name }} — {{ $company->admin->email }}
                    </a>
                </div>
            @endif
        </div>

        <!-- Agents -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Agents ({{ $company->agents->count() }})
            </h3>

            @if($company->agents->count() > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($company->agents as $agent)
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center flex-shrink-0">
                                    <span class="text-sm font-bold text-primary-600 dark:text-primary-400">{{ strtoupper(substr($agent->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $agent->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $agent->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ $agent->qrSlots->count() }} QR codes</span>
                                <span>{{ $agent->listings->count() }} listings</span>
                                <a href="{{ route('admin.users.show', $agent) }}" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">View</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No agents yet.</p>
            @endif
        </div>
    </div>
</x-layouts.app>

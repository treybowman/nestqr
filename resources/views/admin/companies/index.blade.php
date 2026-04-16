@section('title', 'Companies')

<x-layouts.app>
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Companies</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage company plan accounts.</p>
        </div>

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Account Owner</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Agents</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Since</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($companies as $company)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        @if($company->logo_url)
                                            <img src="{{ $company->logo_url }}" alt="" class="w-8 h-8 rounded-lg object-contain">
                                        @else
                                            <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                                <span class="text-xs font-bold text-purple-600 dark:text-purple-400">{{ strtoupper(substr($company->name, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $company->name }}</p>
                                            @if($company->billing_email)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $company->billing_email }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($company->admin)
                                        <a href="{{ route('admin.users.show', $company->admin) }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                            {{ $company->admin->name }}
                                        </a>
                                    @else
                                        <span class="text-sm text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $company->agents_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $company->plan_start_date?->format('M j, Y') ?? $company->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                    <a href="{{ route('admin.companies.show', $company) }}" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">View</a>
                                    <form method="POST" action="{{ route('admin.companies.destroy', $company) }}" class="inline"
                                          onsubmit="return confirm('Delete company {{ addslashes($company->name) }}? This does not delete the users.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">No companies found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($companies->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $companies->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>

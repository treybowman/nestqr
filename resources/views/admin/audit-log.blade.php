@section('title', 'Audit Log')

<x-layouts.app>
    <div class="max-w-7xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Audit Log</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Admin action history.</p>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" class="card p-4 mb-6 flex flex-col sm:flex-row gap-3">
            <select name="action" class="input-field sm:w-48">
                <option value="">All Actions</option>
                @foreach($actions as $action)
                    <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                        {{ str_replace('_', ' ', ucfirst($action)) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
            @if(request()->hasAny(['action', 'admin_id']))
                <a href="{{ route('admin.audit-log') }}" class="btn-secondary">Clear</a>
            @endif
        </form>

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Admin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($logs as $log)
                            @php
                                $actionColors = [
                                    'delete_user'  => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    'impersonate'  => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                    'update_user'  => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'plan_change'  => 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400',
                                    'delete_company' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                ];
                                $actionColor = $actionColors[$log->action] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400';
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <span title="{{ $log->created_at->format('Y-m-d H:i:s') }}">
                                        {{ $log->created_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $log->admin?->name ?? 'Deleted Admin' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $actionColor }}">
                                        {{ str_replace('_', ' ', $log->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $log->description }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">No audit log entries yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>

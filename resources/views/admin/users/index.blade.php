@section('title', 'Manage Users')

<x-layouts.app>
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Manage Users</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">View and manage all registered users.</p>
        </div>

        @livewire('admin.user-management')
    </div>
</x-layouts.app>

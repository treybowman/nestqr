@section('title', 'Manage Domains')

<x-layouts.app>
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Manage Domains</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure active domains and their market regions.</p>
        </div>

        @livewire('admin.domain-management')
    </div>
</x-layouts.app>

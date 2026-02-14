<x-layouts.app>
    <x-slot:title>Settings</x-slot:title>
    <div class="max-w-4xl mx-auto space-y-8">
        <livewire:settings.profile-settings />
        @if(auth()->user()->hasCustomBranding())
            <livewire:settings.branding-settings />
        @endif
        <livewire:settings.preference-settings />
        <livewire:settings.danger-zone />
    </div>
</x-layouts.app>

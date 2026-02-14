<?php

namespace App\Livewire\Settings;

use App\Models\ActiveDomain;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Preferences')]
class PreferenceSettings extends Component
{
    public string $themePreference = 'system';
    public string $preferredDomain = '';
    public Collection $domains;

    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $this->themePreference = $user->theme_preference ?? 'system';
        $this->preferredDomain = $user->preferred_domain ?? '';
        $this->domains = ActiveDomain::active()->orderBy('market_name')->get();
    }

    public function save(): void
    {
        $validDomains = $this->domains->pluck('domain')->toArray();

        $this->validate([
            'themePreference' => ['required', 'in:light,dark,system'],
            'preferredDomain' => ['nullable', 'string', 'in:' . implode(',', array_merge([''], $validDomains))],
        ], [
            'themePreference.in' => 'Please select a valid theme option.',
            'preferredDomain.in' => 'Please select a valid domain.',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $user->update([
            'theme_preference' => $this->themePreference,
            'preferred_domain' => $this->preferredDomain ?: null,
        ]);

        session()->flash('message', 'Preferences updated successfully.');
    }

    public function render()
    {
        return view('livewire.settings.preference-settings');
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\ActiveDomain;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Domain Management')]
class DomainManagement extends Component
{
    public Collection $domains;
    public string $newDomain = '';
    public string $newMarketName = '';
    public bool $showAddForm = false;

    public function mount(): void
    {
        abort_unless(auth()->user()->is_admin, 403, 'Unauthorized. Admin access required.');

        $this->loadDomains();
    }

    public function loadDomains(): void
    {
        $this->domains = ActiveDomain::orderBy('market_name')->get();
    }

    public function addDomain(): void
    {
        abort_unless(auth()->user()->is_admin, 403);

        $this->validate([
            'newDomain' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]?\.[a-zA-Z]{2,}$/',
                'unique:active_domains,domain',
            ],
            'newMarketName' => ['required', 'string', 'max:255'],
        ], [
            'newDomain.required' => 'Please enter a domain name.',
            'newDomain.regex' => 'Please enter a valid domain name (e.g., nestqr.com).',
            'newDomain.unique' => 'This domain already exists.',
            'newMarketName.required' => 'Please enter a market name for this domain.',
        ]);

        ActiveDomain::create([
            'domain' => $this->newDomain,
            'market_name' => $this->newMarketName,
            'is_active' => true,
            'launched_at' => now(),
        ]);

        $this->newDomain = '';
        $this->newMarketName = '';
        $this->showAddForm = false;

        $this->loadDomains();

        session()->flash('message', 'Domain added successfully.');
    }

    public function toggleActive(int $id): void
    {
        abort_unless(auth()->user()->is_admin, 403);

        $domain = ActiveDomain::findOrFail($id);
        $domain->update(['is_active' => ! $domain->is_active]);

        $this->loadDomains();

        $status = $domain->fresh()->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Domain \"{$domain->domain}\" {$status}.");
    }

    public function deleteDomain(int $id): void
    {
        abort_unless(auth()->user()->is_admin, 403);

        $domain = ActiveDomain::findOrFail($id);
        $domainName = $domain->domain;

        $domain->delete();

        $this->loadDomains();

        session()->flash('message', "Domain \"{$domainName}\" deleted successfully.");
    }

    public function render()
    {
        return view('livewire.admin.domain-management');
    }
}

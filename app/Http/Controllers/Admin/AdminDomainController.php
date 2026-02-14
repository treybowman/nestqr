<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActiveDomain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminDomainController extends Controller
{
    /**
     * Display a list of all active domains.
     */
    public function index(): View
    {
        $domains = ActiveDomain::query()
            ->orderBy('market_name')
            ->get();

        return view('admin.domains.index', [
            'domains' => $domains,
        ]);
    }

    /**
     * Store a newly created domain.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'domain' => ['required', 'string', 'max:255', Rule::unique('active_domains', 'domain')],
            'market_name' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'launched_at' => ['nullable', 'date'],
        ]);

        ActiveDomain::create([
            'domain' => $validated['domain'],
            'market_name' => $validated['market_name'],
            'is_active' => $validated['is_active'] ?? true,
            'launched_at' => $validated['launched_at'] ?? now(),
        ]);

        return redirect()->route('admin.domains.index')
            ->with('success', 'Domain created successfully.');
    }

    /**
     * Update the specified domain.
     */
    public function update(Request $request, ActiveDomain $domain): RedirectResponse
    {
        $validated = $request->validate([
            'domain' => ['required', 'string', 'max:255', Rule::unique('active_domains', 'domain')->ignore($domain->id)],
            'market_name' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'launched_at' => ['nullable', 'date'],
        ]);

        $domain->update($validated);

        return redirect()->route('admin.domains.index')
            ->with('success', 'Domain updated successfully.');
    }

    /**
     * Delete the specified domain.
     */
    public function destroy(ActiveDomain $domain): RedirectResponse
    {
        $domain->delete();

        return redirect()->route('admin.domains.index')
            ->with('success', 'Domain deleted successfully.');
    }
}

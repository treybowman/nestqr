<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminCompanyController extends Controller
{
    public function index(): View
    {
        $companies = Company::withCount('agents')
            ->with('admin')
            ->latest()
            ->paginate(25);

        return view('admin.companies.index', [
            'companies' => $companies,
        ]);
    }

    public function show(Company $company): View
    {
        $company->load(['admin', 'agents.qrSlots', 'agents.listings']);

        return view('admin.companies.show', [
            'company' => $company,
        ]);
    }

    public function destroy(Company $company): RedirectResponse
    {
        AdminAuditLog::record(
            'delete_company',
            "Deleted company \"{$company->name}\"",
            'Company',
            $company->id
        );

        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', "Company \"{$company->name}\" deleted.");
    }
}

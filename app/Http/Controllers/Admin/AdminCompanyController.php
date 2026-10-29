<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCompanyController extends Controller
{
    /**
     * Display the featured companies manager (PUBLIC "TRUSTED BY" SHOWCASE).
     */
    public function index(): View
    {
        $companies = Company::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Store a new featured company.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'industry' => 'nullable|string|max:120',
            'website' => 'nullable|url|max:255',
            'logo_url' => 'nullable|url|max:500',
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $logoUrl = $validated['logo_url'] ?? null;

        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            $filename = 'company-' . Str::slug($validated['name']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/companies'), $filename);
            $logoUrl = 'images/companies/' . $filename;
        }

        $company = Company::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(4),
            'description' => $validated['description'] ?? null,
            'industry' => $validated['industry'] ?? null,
            'website' => $validated['website'] ?? null,
            'logo_url' => $logoUrl,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLog::record('create_company', "Added featured company: {$company->name}", 'company', $company->id);

        return back()->with('success', "Company '{$company->name}' added to the public showcase.");
    }

    /**
     * Update an existing featured company.
     */
    public function update(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'industry' => 'nullable|string|max:120',
            'website' => 'nullable|url|max:255',
            'logo_url' => 'nullable|url|max:500',
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $logoUrl = $validated['logo_url'] ?? $company->logo_url;

        if ($request->hasFile('logo_file')) {
            if ($company->logo_url && file_exists(public_path($company->logo_url))) {
                @unlink(public_path($company->logo_url));
            }
            $file = $request->file('logo_file');
            $filename = 'company-' . Str::slug($validated['name']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/companies'), $filename);
            $logoUrl = 'images/companies/' . $filename;
        }

        $company->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'industry' => $validated['industry'] ?? null,
            'website' => $validated['website'] ?? null,
            'logo_url' => $logoUrl,
            'sort_order' => $validated['sort_order'] ?? $company->sort_order,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLog::record('update_company', "Updated featured company: {$company->name}", 'company', $company->id);

        return back()->with('success', "Company '{$company->name}' updated successfully.");
    }

    /**
     * Delete a featured company.
     */
    public function destroy(Company $company): RedirectResponse
    {
        $name = $company->name;
        $company->delete();

        AuditLog::record('delete_company', "Removed featured company: {$name}", 'company', null);

        return back()->with('success', "Company '{$name}' removed from the public showcase.");
    }
}
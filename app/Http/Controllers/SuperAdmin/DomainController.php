<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Domain;
use App\Models\Tenant;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $domains = Domain::with('tenant')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('super-admin.domains.index', compact('domains'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tenants = Tenant::where('status', 'active')->get();
        return view('super-admin.domains.create', compact('tenants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'domain' => 'required|string|max:255',
            'subdomain' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
            'is_https' => 'boolean',
            'status' => 'required|in:active,inactive,pending',
        ]);

        // Check for domain uniqueness
        $existingDomain = Domain::where('domain', $request->domain)
            ->where('subdomain', $request->subdomain)
            ->first();

        if ($existingDomain) {
            return back()->withErrors(['domain' => 'This domain/subdomain combination already exists.']);
        }

        $domain = Domain::create($request->all());

        return redirect()->route('super-admin.domains.index')
            ->with('success', 'Domain created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Domain $domain)
    {
        $domain->load('tenant');
        return view('super-admin.domains.show', compact('domain'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Domain $domain)
    {
        $tenants = Tenant::where('status', 'active')->get();
        return view('super-admin.domains.edit', compact('domain', 'tenants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Domain $domain)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'domain' => 'required|string|max:255',
            'subdomain' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
            'is_https' => 'boolean',
            'status' => 'required|in:active,inactive,pending',
        ]);

        // Check for domain uniqueness (excluding current domain)
        $existingDomain = Domain::where('domain', $request->domain)
            ->where('subdomain', $request->subdomain)
            ->where('id', '!=', $domain->id)
            ->first();

        if ($existingDomain) {
            return back()->withErrors(['domain' => 'This domain/subdomain combination already exists.']);
        }

        $domain->update($request->all());

        return redirect()->route('super-admin.domains.show', $domain)
            ->with('success', 'Domain updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Domain $domain)
    {
        // Prevent deletion of primary domain if there are other domains
        if ($domain->is_primary && $domain->tenant->domains()->count() > 1) {
            return back()->with('error', 'Cannot delete primary domain. Set another domain as primary first.');
        }

        $domain->delete();

        return redirect()->route('super-admin.domains.index')
            ->with('success', 'Domain deleted successfully.');
    }

    /**
     * Verify domain ownership
     */
    public function verify(Domain $domain)
    {
        // In a real implementation, you would check DNS records or use domain verification
        $domain->markAsVerified();

        return back()->with('success', 'Domain verified successfully.');
    }
}

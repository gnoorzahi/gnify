<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::with(['primaryDomain', 'users'])
            ->withCount(['domains', 'users', 'products'])
            ->paginate(15);

        return view('super-admin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tenants,slug',
            'description' => 'nullable|string',
            'plan' => 'required|in:basic,pro,enterprise',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $tenant = Tenant::create([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
            'plan' => $request->plan,
            'status' => $request->status,
            'trial_ends_at' => now()->addDays(30), // 30 day trial
        ]);

        return redirect()->route('super-admin.tenants.show', $tenant)
            ->with('success', 'Tenant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        $tenant->load(['domains', 'users', 'categories', 'products.category']);

        return view('super-admin.tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        return view('super-admin.tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenants,slug,' . $tenant->id,
            'description' => 'nullable|string',
            'plan' => 'required|in:basic,pro,enterprise',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $tenant->update($request->only([
            'name', 'slug', 'description', 'plan', 'status'
        ]));

        return redirect()->route('super-admin.tenants.show', $tenant)
            ->with('success', 'Tenant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        // Prevent deletion if tenant has users or data
        if ($tenant->users()->count() > 0 || $tenant->products()->count() > 0) {
            return back()->with('error', 'Cannot delete tenant with existing users or products.');
        }

        $tenant->delete();

        return redirect()->route('super-admin.tenants.index')
            ->with('success', 'Tenant deleted successfully.');
    }
}

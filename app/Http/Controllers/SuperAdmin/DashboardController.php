<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Domain;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('status', 'active')->count(),
            'total_domains' => Domain::count(),
            'verified_domains' => Domain::whereNotNull('verified_at')->count(),
            'total_users' => User::whereNotNull('tenant_id')->count(),
            'super_admins' => User::where('is_super_admin', true)->count(),
        ];

        $recent_tenants = Tenant::with('primaryDomain')
            ->latest()
            ->take(5)
            ->get();

        $recent_domains = Domain::with('tenant')
            ->latest()
            ->take(5)
            ->get();

        return view('super-admin.dashboard', compact('stats', 'recent_tenants', 'recent_domains'));
    }
}

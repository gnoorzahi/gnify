<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Domain;
use App\Models\Tenant;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        
        // Skip tenant resolution for localhost and super admin routes
        if ($host === 'localhost' || str_contains($request->path(), 'super-admin')) {
            return $next($request);
        }

        // Find domain and tenant
        $domain = Domain::findByDomain($host);
        
        if (!$domain || !$domain->tenant) {
            abort(404, 'Tenant not found for domain: ' . $host);
        }

        $tenant = $domain->tenant;

        // Check if tenant is active
        if (!$tenant->isActive()) {
            abort(503, 'This shop is currently unavailable.');
        }

        // Set the current tenant globally
        app()->instance('current_tenant', $tenant);
        app()->instance('current_domain', $domain);

        // Set tenant context for database queries
        config(['app.current_tenant_id' => $tenant->id]);

        return $next($request);
    }
}

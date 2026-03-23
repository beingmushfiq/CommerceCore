<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Store;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = null;

        // 1. Resolve by Authenticated User (Admin/Owner dashboard context)
        if ($request->hasSession() && auth()->check()) {
            // Fetch their first store for now as a fallback
            $store = Store::where('owner_id', auth()->id())->first();
            if ($store) {
                $tenantId = $store->id;
            }
        }

        // 2. Resolve by Subdomain/Host (Storefront context)
        if (!$tenantId) {
            $host = $request->getHost();
            $store = Store::where('domain', $host)->first();
            if ($store) {
                $tenantId = $store->id;
            }
        }

        // 3. Resolve by Header (API context)
        if (!$tenantId && $request->hasHeader('X-Store-ID')) {
            $tenantId = $request->header('X-Store-ID');
        }

        if ($tenantId) {
            // Bind the active tenant ID into the IOC container
            app()->instance('current_tenant_id', $tenantId);
        }

        return $next($request);
    }
}

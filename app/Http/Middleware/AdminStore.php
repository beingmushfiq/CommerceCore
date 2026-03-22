<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminStore
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super admin can access everything
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Store owner - resolve their first store
        if ($user->isStoreOwner()) {
            $store = $user->ownedStores()->first();
            if (!$store) {
                abort(403, 'No store assigned');
            }
            session(['admin_store_id' => $store->id]);
            $request->merge(['admin_store' => $store]);
            view()->share('adminStore', $store);
            return $next($request);
        }

        // Staff - use their assigned store
        if ($user->isStaff() && $user->store_id) {
            $store = $user->store;
            session(['admin_store_id' => $store->id]);
            $request->merge(['admin_store' => $store]);
            view()->share('adminStore', $store);
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}

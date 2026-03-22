<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Order;
use App\Services\StoreService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private StoreService $storeService) {}

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isSuperAdmin()) {
            $stores = Store::with('owner', 'plan')->latest()->get();
            $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_price');
            $totalOrders = Order::count();
            $totalStores = Store::count();
            $recentOrders = Order::with('store')->latest()->take(10)->get();

            return view('admin.dashboard', compact(
                'stores', 'totalRevenue', 'totalOrders', 'totalStores', 'recentOrders'
            ));
        }

        // Store owner/staff dashboard
        $store = $request->get('admin_store') ?? $user->ownedStores()->first();
        if (!$store) {
            return redirect()->route('admin.stores.create');
        }

        $stats = $this->storeService->getStats($store);
        $recentOrders = $store->orders()->with('items.product')->latest()->take(5)->get();

        return view('admin.dashboard', compact('store', 'stats', 'recentOrders'));
    }
}

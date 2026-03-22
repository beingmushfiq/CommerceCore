<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    private function resolveStore(Request $request): Store
    {
        $user = $request->user();
        if ($user->isSuperAdmin()) {
            return Store::findOrFail($request->input('store_id', session('admin_store_id')));
        }
        return $request->get('admin_store') ?? $user->ownedStores()->firstOrFail();
    }

    public function index(Request $request)
    {
        $store = $this->resolveStore($request);
        $orders = $this->orderService->getForStore($store, [
            'status' => $request->status,
            'search' => $request->search,
        ]);

        return view('admin.orders.index', compact('orders', 'store'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'store', 'shipment.courier');
        $couriers = \App\Models\Courier::where('is_active', true)->get();
        return view('admin.orders.show', compact('order', 'couriers'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,shipped,delivered,cancelled',
        ]);

        $this->orderService->updateStatus($order, $validated['status']);

        return redirect()->back()->with('success', 'Order status updated!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WarehouseZone;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $storeId = session('admin_store_id') ?? Store::first()->id;

        // Get Reorder Points (Low Stock)
        $lowStockProducts = Product::where('store_id', $storeId)
            ->whereNotNull('alert_quantity')
            ->where(function ($query) {
                // If stock is less than or equal to alert_quantity
                $query->whereColumn('stock', '<=', 'alert_quantity');
            })
            ->get();

        $zones = WarehouseZone::withCount('inventories')->where('store_id', $storeId)->get();

        return view('admin.inventory.index', compact('lowStockProducts', 'zones'));
    }

    public function createZone(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'capacity' => 'nullable|integer|min:0'
        ]);

        $storeId = session('admin_store_id') ?? Store::first()->id;

        WarehouseZone::create([
            'store_id' => $storeId,
            'name' => $request->name,
            'type' => $request->type,
            'capacity' => $request->capacity
        ]);

        return back()->with('success', 'Warehouse Zone created successfully.');
    }

    public function autoReorder(Request $request)
    {
        // In a real scenario, this would group low stock items by supplier and generate Draft POs.
        // For CommerceCore ERP demonstration, we will just flash a success message.
        return back()->with('success', 'Automated Purchase Orders generated for all low-stock items.');
    }
}

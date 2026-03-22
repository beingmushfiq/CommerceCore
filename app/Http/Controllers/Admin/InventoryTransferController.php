<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransfer;
use App\Models\Store;
use App\Models\Branch;
use App\Models\Product;
use App\Models\BranchInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryTransferController extends Controller
{
    public function index()
    {
        $storeId = session('admin_store_id') ?? Store::first()->id;
        $transfers = InventoryTransfer::whereHas('product', function($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })
            ->with(['product', 'fromBranch', 'toBranch'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.inventory-transfers.index', compact('transfers'));
    }

    public function create()
    {
        $storeId = session('admin_store_id') ?? Store::first()->id;
        $store = Store::findOrFail($storeId);
        $branches = $store->branches;
        $products = Product::where('store_id', $storeId)->get();

        return view('admin.inventory-transfers.create', compact('branches', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'from_branch_id' => 'required|exists:branches,id',
            'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        return DB::transaction(function() use ($validated) {
            // Check source stock
            $sourceInventory = BranchInventory::firstOrCreate(
                ['branch_id' => $validated['from_branch_id'], 'product_id' => $validated['product_id']],
                ['stock' => 0]
            );

            if ($sourceInventory->stock < $validated['quantity']) {
                return back()->withErrors(['quantity' => 'Insufficient stock in source branch.'])->withInput();
            }

            // Perform transfer
            $sourceInventory->decrement('stock', $validated['quantity']);
            
            $destInventory = BranchInventory::firstOrCreate(
                ['branch_id' => $validated['to_branch_id'], 'product_id' => $validated['product_id']],
                ['stock' => 0]
            );
            $destInventory->increment('stock', $validated['quantity']);

            InventoryTransfer::create($validated + ['status' => 'completed']);

            return redirect()->route('admin.inventory-transfers.index')->with('success', 'Stock transferred successfully!');
        });
    }

    public function destroy(InventoryTransfer $inventoryTransfer)
    {
        // For audit purposes, we usually don't delete transfers, but keeping standard boilerplate
        $inventoryTransfer->delete();
        return redirect()->route('admin.inventory-transfers.index')->with('success', 'Log deleted.');
    }
}

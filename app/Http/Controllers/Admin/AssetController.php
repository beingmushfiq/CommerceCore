<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Store;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::latest()->paginate(20);
        return view('admin.assets.index', compact('assets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'purchase_price' => 'required|numeric',
            'purchase_date' => 'required|date',
            'depreciation_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:in_use,maintenance,sold,disposed'
        ]);

        $storeId = session('admin_store_id') ?? Store::first()->id;

        Asset::create(array_merge($validated, [
            'store_id' => $storeId,
            'current_value' => $validated['purchase_price']
        ]));

        return back()->with('success', 'Asset added successfully.');
    }
}

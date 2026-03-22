<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    /**
     * Display the POS interface.
     */
    public function index()
    {
        // For POS, we'll want to display all products for the active store
        // Let's assume the tenant is global for now, or use AdminStore middleware attached store
        // For simplicity, we just fetch all products if we are super_admin, or filter by store ID
        
        $categories = Category::withCount('products')->orderBy('name')->get();
        
        $products = Product::where('status', 'active')
            ->orderBy('name')
            ->paginate(50);

        return view('admin.pos.index', compact('categories', 'products'));
    }

    /**
     * Process a POS Checkout.
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'payment_method' => 'required|string|in:cash,card,bank_transfer',
            'amount_paid' => 'required|numeric|min:0',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Resolve store context from middleware or user
            $store = $request->get('admin_store') ?? $request->user()->store ?? Store::first();
            $storeId = $store->id; 

            // Calculate total and prepare order
            $totalAmount = 0;
            foreach ($validated['items'] as $itemData) {
                $totalAmount += $itemData['price'] * $itemData['quantity'];
                
                // Deduct stock
                $product = Product::findOrFail($itemData['id']);
                if ($product->stock < $itemData['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }
                $product->decrement('stock', $itemData['quantity']);
            }

            // Create the order
            $order = Order::create([
                'store_id' => $storeId,
                'user_id' => auth()->id(), // the cashier
                'order_number' => 'POS-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'customer_name' => $validated['customer_name'] ?? 'Walk-in Customer',
                'customer_email' => null,
                'phone' => $validated['customer_phone'] ?? null,
                'address' => 'In-Store POS',
                'subtotal' => $totalAmount,
                'tax' => 0.00,
                'total_price' => $totalAmount,
                'status' => 'paid', // POS is immediate payment
                'lifecycle_status' => 'DELIVERED', // Instant fulfillment
                'notes' => 'POS Sale - ' . ucfirst($validated['payment_method']),
            ]);

            // Create items
            foreach ($validated['items'] as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $itemData['id'],
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order processed successfully.',
                'order_id' => $order->order_number
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }
}

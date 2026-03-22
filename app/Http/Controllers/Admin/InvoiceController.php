<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Show the invoice for an order (A4 or Thermal).
     */
    public function show(Order $order, string $type = 'a4')
    {
        // Ensure relationships are loaded
        $order->load(['items.product', 'store', 'user', 'assignedAgent']);

        if ($type === 'thermal') {
            return view('admin.invoices.thermal', compact('order'));
        }

        return view('admin.invoices.a4', compact('order'));
    }
}

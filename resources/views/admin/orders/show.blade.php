<x-layouts.admin>
    <x-slot:header>Order {{ $order->order_number }}</x-slot:header>

    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Order Header --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-display font-bold text-surface-800 dark:text-white flex items-center gap-3">
                        {{ $order->order_number }}
                        <a href="{{ route('admin.orders.invoice', [$order, 'a4']) }}" class="text-xs bg-surface-100 hover:bg-surface-200 dark:bg-surface-700 dark:hover:bg-surface-600 text-surface-600 dark:text-surface-300 px-3 py-1.5 rounded-lg flex items-center gap-1 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Print Invoice
                        </a>
                        <a href="{{ route('admin.orders.invoice', [$order, 'thermal']) }}" class="text-xs bg-surface-100 hover:bg-surface-200 dark:bg-surface-700 dark:hover:bg-surface-600 text-surface-600 dark:text-surface-300 px-3 py-1.5 rounded-lg flex items-center gap-1 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Thermal Receipt
                        </a>
                    </h2>
                    <p class="text-sm text-surface-500 mt-1">Placed {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex items-center gap-3">
                    @csrf @method('PUT')
                    <select name="status" class="px-4 py-2 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                        @foreach(['pending', 'paid', 'shipped', 'delivered', 'cancelled'] as $s)
                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-semibold rounded-xl transition-colors">Update</button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Customer Info --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6">
                <h3 class="text-sm font-semibold text-surface-500 uppercase tracking-wider mb-4">Customer</h3>
                <div class="space-y-3">
                    <div><p class="text-sm text-surface-400">Name</p><p class="text-sm font-medium text-surface-800 dark:text-white">{{ $order->customer_name }}</p></div>
                    <div><p class="text-sm text-surface-400">Email</p><p class="text-sm font-medium text-surface-800 dark:text-white">{{ $order->customer_email }}</p></div>
                    @if($order->phone)<div><p class="text-sm text-surface-400">Phone</p><p class="text-sm font-medium text-surface-800 dark:text-white">{{ $order->phone }}</p></div>@endif
                    @if($order->address)<div><p class="text-sm text-surface-400">Address</p><p class="text-sm font-medium text-surface-800 dark:text-white">{{ $order->address }}</p></div>@endif
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6">
                <h3 class="text-sm font-semibold text-surface-500 uppercase tracking-wider mb-4">Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between"><span class="text-sm text-surface-500">Subtotal</span><span class="text-sm font-medium text-surface-800 dark:text-white">${{ number_format($order->subtotal, 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-sm text-surface-500">Tax</span><span class="text-sm font-medium text-surface-800 dark:text-white">${{ number_format($order->tax, 2) }}</span></div>
                    <div class="flex justify-between border-t border-surface-200 dark:border-surface-700 pt-3"><span class="text-sm font-semibold text-surface-800 dark:text-white">Total</span><span class="text-lg font-bold gradient-text">${{ number_format($order->total_price, 2) }}</span></div>
                </div>
            </div>
        </div>

        {{-- Shipment Tracking --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6">
            <h3 class="text-sm font-semibold text-surface-500 uppercase tracking-wider mb-4">Shipment & Logistics</h3>
            
            @if($order->shipment)
                <div class="flex items-center justify-between p-4 bg-surface-50 dark:bg-surface-900/50 rounded-xl">
                    <div>
                        <p class="text-sm font-bold text-surface-800 dark:text-white">{{ $order->shipment->courier->name ?? 'Courier' }}</p>
                        <p class="text-xs text-surface-400">Tracking: <span class="font-mono text-primary-600">{{ $order->shipment->tracking_number ?? 'N/A' }}</span></p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                            {{ ucfirst($order->shipment->status) }}
                        </span>
                        <p class="text-[10px] text-surface-400 mt-1">Cost: ${{ number_format($order->shipment->shipping_cost, 2) }}</p>
                    </div>
                </div>
                
                <form action="{{ route('admin.shipments.status', $order->shipment) }}" method="POST" class="mt-4 flex gap-2">
                    @csrf
                    <select name="status" class="flex-1 text-sm border-surface-200 rounded-lg dark:bg-surface-700 dark:text-white">
                        @foreach(['picked', 'in_transit', 'delivered', 'returned', 'cancelled'] as $s)
                            <option value="{{ $s }}" {{ $order->shipment->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-primary-500 text-white text-sm font-semibold rounded-lg">Update Status</button>
                </form>
            @else
                <form action="{{ route('admin.orders.dispatch', $order) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs text-surface-400 mb-1">Courier</label>
                            <select name="courier_id" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-700 dark:text-white">
                                <option value="">Select Courier</option>
                                @foreach($couriers as $courier)
                                    <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-surface-400 mb-1">Tracking #</label>
                            <input type="text" name="tracking_number" placeholder="Track-1234..." class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs text-surface-400 mb-1">Shipping Cost</label>
                            <input type="number" step="0.01" name="shipping_cost" value="0.00" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-700 dark:text-white">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-colors shadow-lg shadow-primary-500/20">
                        Dispatch Shipment
                    </button>
                </form>
            @endif
        </div>

        {{-- Order Items --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700">
                <h3 class="text-sm font-semibold text-surface-500 uppercase tracking-wider">Items ({{ $order->items->count() }})</h3>
            </div>
            <div class="divide-y divide-surface-100 dark:divide-surface-700">
                @foreach($order->items as $item)
                <div class="flex items-center gap-4 px-6 py-4">
                    <div class="w-14 h-14 rounded-xl bg-surface-100 dark:bg-surface-700 overflow-hidden flex-shrink-0">
                        @if($item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-surface-800 dark:text-white">{{ $item->product->name }}</p>
                        <p class="text-xs text-surface-400">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                    </div>
                    <p class="text-sm font-semibold text-surface-800 dark:text-white">${{ number_format($item->price * $item->quantity, 2) }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.admin>

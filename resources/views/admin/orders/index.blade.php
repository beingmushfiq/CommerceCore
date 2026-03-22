<x-layouts.admin>
    <x-slot:header>Orders</x-slot:header>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-surface-800 dark:text-white">Orders</h2>
                <p class="text-sm text-surface-500 mt-1">Track and manage customer orders</p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 text-sm font-medium rounded-xl {{ !request('status') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'bg-white dark:bg-surface-800 text-surface-600 dark:text-surface-400 border border-surface-200 dark:border-surface-700' }} transition-colors">All</a>
            @foreach(['pending', 'paid', 'shipped', 'delivered', 'cancelled'] as $status)
            <a href="{{ route('admin.orders.index', ['status' => $status]) }}" class="px-4 py-2 text-sm font-medium rounded-xl {{ request('status') === $status ? 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'bg-white dark:bg-surface-800 text-surface-600 dark:text-surface-400 border border-surface-200 dark:border-surface-700' }} transition-colors">{{ ucfirst($status) }}</a>
            @endforeach
        </div>

        {{-- Orders Table --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-surface-50 dark:bg-surface-700/50">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Order</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Customer</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Items</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Status</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Total</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Risk Score</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Date</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                        @forelse($orders as $order)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors">
                            <td class="px-6 py-4"><span class="text-sm font-semibold text-primary-600 dark:text-primary-400">{{ $order->order_number }}</span></td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-surface-800 dark:text-white">{{ $order->customer_name }}</p>
                                <p class="text-xs text-surface-400">{{ $order->customer_email }}</p>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-surface-600 dark:text-surface-300">{{ $order->items->count() }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-semibold text-surface-800 dark:text-white">${{ number_format($order->total_price, 2) }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 text-[10px] font-black rounded-lg {{ $order->fraud_score > 50 ? 'bg-rose-100 text-rose-700' : 'bg-surface-100 text-surface-600' }}">
                                    {{ $order->fraud_score }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-surface-500">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline font-medium">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-surface-300 dark:text-surface-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <p class="text-surface-500 dark:text-surface-400">No orders yet</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">{{ $orders->links() }}</div>
            @endif
        </div>
    </div>
</x-layouts.admin>

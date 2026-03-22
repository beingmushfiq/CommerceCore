<x-layouts.admin>
    <x-slot:header>Dashboard</x-slot:header>

    @php
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
    @endphp

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @if($isSuperAdmin)
        <div class="card-hover bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-surface-500 dark:text-surface-400">Total Stores</p>
                    <p class="text-3xl font-display font-bold text-surface-800 dark:text-white mt-1">{{ number_format($totalStores) }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
        </div>
        <div class="card-hover bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-surface-500 dark:text-surface-400">Total Orders</p>
                    <p class="text-3xl font-display font-bold text-surface-800 dark:text-white mt-1">{{ number_format($totalOrders) }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
        </div>
        <div class="card-hover bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-surface-500 dark:text-surface-400">Revenue</p>
                    <p class="text-3xl font-display font-bold text-surface-800 dark:text-white mt-1">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="card-hover bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-surface-500 dark:text-surface-400">Recent Stores</p>
                    <p class="text-3xl font-display font-bold text-surface-800 dark:text-white mt-1">{{ $stores->where('created_at', '>=', now()->subDays(30))->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
            </div>
        </div>
        @else
        {{-- Store Owner Stats --}}
        <div class="card-hover bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-surface-500 dark:text-surface-400">Products</p>
                    <p class="text-3xl font-display font-bold text-surface-800 dark:text-white mt-1">{{ $stats['total_products'] }}</p>
                    <p class="text-xs text-emerald-500 mt-1">{{ $stats['active_products'] }} active</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
        </div>
        <div class="card-hover bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-surface-500 dark:text-surface-400">Total Orders</p>
                    <p class="text-3xl font-display font-bold text-surface-800 dark:text-white mt-1">{{ $stats['total_orders'] }}</p>
                    <p class="text-xs text-amber-500 mt-1">{{ $stats['pending_orders'] }} pending</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
        </div>
        <div class="card-hover bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-surface-500 dark:text-surface-400">Revenue</p>
                    <p class="text-3xl font-display font-bold text-surface-800 dark:text-white mt-1">${{ number_format($stats['total_revenue'], 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="card-hover bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-surface-500 dark:text-surface-400">Pages</p>
                    <p class="text-3xl font-display font-bold text-surface-800 dark:text-white mt-1">{{ $stats['total_pages'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"/></svg>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 flex items-center justify-between">
            <h2 class="text-lg font-display font-semibold text-surface-800 dark:text-white">Recent Orders</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-surface-50 dark:bg-surface-700/50">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">Order</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">Customer</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">Status</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">Total</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline">{{ $order->order_number }}</a>
                        </td>
                        <td class="px-6 py-4 text-sm text-surface-600 dark:text-surface-300">{{ $order->customer_name }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-surface-800 dark:text-white text-right">${{ number_format($order->total_price, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-surface-500 text-right">{{ $order->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-surface-300 dark:text-surface-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <p class="text-surface-500 dark:text-surface-400">No orders yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>

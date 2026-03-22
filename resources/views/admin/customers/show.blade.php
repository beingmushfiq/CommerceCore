<x-layouts.admin>
    <x-slot:header>Customer Details</x-slot:header>

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-display font-bold text-surface-800 dark:text-white flex items-center gap-3">
                Customer Profile
            </h2>
            <a href="{{ route('admin.customers.index') }}" class="text-sm text-surface-500 hover:text-surface-700 dark:hover:text-surface-300 font-medium">← Back to List</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Profile Card --}}
            <div class="md:col-span-1 space-y-6">
                <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm text-center">
                    <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-tr from-primary-500 to-indigo-500 flex items-center justify-center text-white text-3xl font-display font-bold shadow-lg shadow-primary-500/30 mb-4">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <h3 class="font-bold text-surface-800 dark:text-white text-lg">{{ $user->name }}</h3>
                    <p class="text-sm text-surface-500">{{ $user->email }}</p>
                    <div class="mt-4 pt-4 border-t border-surface-100 dark:border-surface-700 flex justify-center">
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-black uppercase {{ $user->customer_rank === 'gold' ? 'bg-amber-100 text-amber-600' : ($user->customer_rank === 'silver' ? 'bg-slate-200 text-slate-600' : 'bg-surface-100 text-surface-500') }} shadow-sm">Rank: {{ $user->customer_rank }}</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm space-y-4">
                    <div>
                        <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest mb-1">Total Spent</p>
                        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($user->total_spent, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest mb-1">Orders Placed</p>
                        <p class="text-2xl font-bold text-surface-800 dark:text-white">{{ $user->order_count }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest mb-1">Loyalty Points</p>
                        <p class="text-2xl font-bold text-amber-500">{{ $user->loyalty_points }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest mb-1">Member Since</p>
                        <p class="text-sm font-semibold text-surface-800 dark:text-white">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="md:col-span-2">
                <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
                    <div class="p-5 border-b border-surface-100 dark:border-surface-700 flex items-center justify-between">
                        <h3 class="font-bold text-surface-800 dark:text-white">Order History</h3>
                    </div>
                    
                    <div class="divide-y divide-surface-100 dark:divide-surface-700">
                        @forelse($orders as $order)
                        <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors">
                            <div>
                                <a href="{{ route('admin.orders.show', $order) }}" class="font-bold text-primary-600 hover:text-primary-700 hover:underline">{{ $order->order_number }}</a>
                                <p class="text-xs text-surface-500 mt-1">{{ $order->created_at->format('M d, Y h:i A') }} • {{ $order->items_count }} items</p>
                            </div>
                            <div class="text-right flex flex-col sm:items-end gap-1">
                                <span class="font-bold text-surface-800 dark:text-white">${{ number_format($order->total_price, 2) }}</span>
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->status === 'paid' || $order->status === 'delivered' ? 'bg-emerald-100 text-emerald-700' : 'bg-surface-100 text-surface-500' }}">{{ $order->status }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="p-8 text-center text-surface-500 text-sm">
                            This customer hasn't placed any orders yet.
                        </div>
                        @endforelse
                    </div>
                    
                    @if($orders->hasPages())
                        <div class="px-5 py-4 border-t border-surface-100 dark:border-surface-700">{{ $orders->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

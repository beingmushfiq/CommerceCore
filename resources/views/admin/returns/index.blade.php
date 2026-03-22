<x-layouts.admin>
    <x-slot:header>Returns & Refunds</x-slot:header>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-surface-800 dark:text-white">Returns & Refunds</h2>
                <p class="text-sm text-surface-500 mt-1">Manage customer returns, damages, and refunds</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="bg-white dark:bg-surface-800 p-5 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1">Pending Returns</p>
                    <h4 class="text-3xl font-bold text-surface-800 dark:text-white">{{ $pendingReturns }}</h4>
                </div>
                <div class="w-12 h-12 rounded-full bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="bg-white dark:bg-surface-800 p-5 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest mb-1">Total Refunded</p>
                    <h4 class="text-3xl font-bold text-surface-800 dark:text-white">${{ number_format($totalRefunded, 2) }}</h4>
                </div>
                <div class="w-12 h-12 rounded-full bg-rose-50 dark:bg-rose-900/30 flex items-center justify-center text-rose-600 dark:text-rose-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
            <div class="p-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-800 flex flex-wrap gap-2">
                <a href="{{ route('admin.returns.index') }}" class="px-4 py-2 text-sm font-semibold rounded-xl transition-all {{ !request('status') ? 'bg-surface-800 text-white dark:bg-white dark:text-surface-900' : 'bg-white dark:bg-surface-700 text-surface-600 dark:text-surface-300 border border-surface-200 dark:border-surface-600 hover:bg-surface-50 dark:hover:bg-surface-600' }}">All</a>
                <a href="{{ route('admin.returns.index', ['status' => 'pending']) }}" class="px-4 py-2 text-sm font-semibold rounded-xl transition-all {{ request('status') == 'pending' ? 'bg-amber-500 text-white' : 'bg-white dark:bg-surface-700 text-surface-600 dark:text-surface-300 border border-surface-200 dark:border-surface-600 hover:bg-surface-50 dark:hover:bg-surface-600' }}">Pending</a>
                <a href="{{ route('admin.returns.index', ['status' => 'refunded']) }}" class="px-4 py-2 text-sm font-semibold rounded-xl transition-all {{ request('status') == 'refunded' ? 'bg-emerald-500 text-white' : 'bg-white dark:bg-surface-700 text-surface-600 dark:text-surface-300 border border-surface-200 dark:border-surface-600 hover:bg-surface-50 dark:hover:bg-surface-600' }}">Refunded</a>
                <a href="{{ route('admin.returns.index', ['status' => 'rejected']) }}" class="px-4 py-2 text-sm font-semibold rounded-xl transition-all {{ request('status') == 'rejected' ? 'bg-rose-500 text-white' : 'bg-white dark:bg-surface-700 text-surface-600 dark:text-surface-300 border border-surface-200 dark:border-surface-600 hover:bg-surface-50 dark:hover:bg-surface-600' }}">Rejected</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-50 dark:bg-surface-700/50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold text-surface-500 uppercase">Return ID</th>
                            <th class="px-6 py-3 text-xs font-semibold text-surface-500 uppercase">Order</th>
                            <th class="px-6 py-3 text-xs font-semibold text-surface-500 uppercase">Reason</th>
                            <th class="px-6 py-3 text-xs font-semibold text-surface-500 uppercase text-right">Refund Amount</th>
                            <th class="px-6 py-3 text-xs font-semibold text-surface-500 uppercase text-center">Status</th>
                            <th class="px-6 py-3 text-xs font-semibold text-surface-500 uppercase text-right">Date</th>
                            <th class="px-6 py-3 text-xs font-semibold text-surface-500 uppercase text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                        @forelse($returns as $return)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-semibold text-surface-800 dark:text-white">{{ $return->return_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $return->order_id) }}" class="text-sm font-bold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline">
                                    {{ $return->order->order_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-surface-600 dark:text-surface-300 truncate max-w-[200px]">{{ $return->reason }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-bold text-surface-800 dark:text-white">${{ number_format($return->total_refund_amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($return->status === 'pending')
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Pending</span>
                                @elseif($return->status === 'refunded')
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Refunded</span>
                                @else
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-surface-100 text-surface-700">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm text-surface-500">{{ $return->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.returns.show', $return) }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 hover:underline">View Details</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-surface-500">
                                No return requests found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($returns->hasPages())
                <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">{{ $returns->links() }}</div>
            @endif
        </div>
    </div>
</x-layouts.admin>

<x-layouts.admin>
    <x-slot:header>Customers</x-slot:header>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-display font-bold text-surface-800 dark:text-white">Customer Hub</h2>
                <p class="text-sm text-surface-500 mt-1">Manage and analyze your customer base</p>
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <input type="text" placeholder="Search customers..." class="pl-10 pr-4 py-2.5 bg-white dark:bg-surface-800 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white shadow-sm w-64">
                    <svg class="w-5 h-5 text-surface-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white dark:bg-surface-800 p-5 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-primary-500 uppercase tracking-widest mb-1">Total Customers</p>
                    <h4 class="text-3xl font-bold text-surface-800 dark:text-white">{{ number_format($totalCustomers) }}</h4>
                </div>
                <div class="w-12 h-12 rounded-full bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div class="bg-white dark:bg-surface-800 p-5 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Customer LTV (Total)</p>
                    <h4 class="text-3xl font-bold text-surface-800 dark:text-white">${{ number_format($totalRevenue, 2) }}</h4>
                </div>
                <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 10v1m-2.599-2c-.519-.598-1-1.402-1-2 0-1.105.895-2 2-2m2.599 2c0 .598-.481 1.402-1 2M12 11c-1.105 0-2 .895-2 2s.895 2 2 2 2-.895 2-2-.895-2-2-2z"/></svg>
                </div>
            </div>
            <div class="bg-white dark:bg-surface-800 p-5 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1">Average SPEND</p>
                    <h4 class="text-3xl font-bold text-surface-800 dark:text-white">${{ number_format($averageOrderValue, 2) }}</h4>
                </div>
                <div class="w-12 h-12 rounded-full bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-surface-50 dark:bg-surface-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-surface-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-surface-500 uppercase">Rank</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-surface-500 uppercase">Orders</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-surface-500 uppercase">Total Spent</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-surface-500 uppercase">Points</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-surface-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                        @forelse($customers as $customer)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold">
                                        {{ substr($customer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-surface-800 dark:text-white">{{ $customer->name }}</p>
                                        <p class="text-xs text-surface-400">{{ $customer->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $customer->customer_rank === 'gold' ? 'bg-amber-100 text-amber-600' : ($customer->customer_rank === 'silver' ? 'bg-slate-200 text-slate-600' : 'bg-surface-100 text-surface-500') }}">{{ $customer->customer_rank }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-bold text-surface-800 dark:text-white">{{ $customer->order_count }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($customer->total_spent, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-surface-500 font-bold">
                                {{ $customer->loyalty_points }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="p-2 text-surface-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors inline-block">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-surface-500">
                                No customers registered yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($customers->hasPages())
                <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">{{ $customers->links() }}</div>
            @endif
        </div>
    </div>
</x-layouts.admin>

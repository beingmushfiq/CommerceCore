<x-layouts.admin>
    <x-slot:header>Suppliers</x-slot:header>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-display font-bold text-surface-800 dark:text-white">Suppliers</h2>
                <p class="text-sm text-surface-500 mt-1">Manage your supply chain partners</p>
            </div>
            <a href="{{ route('admin.suppliers.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/25 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Supplier
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white dark:bg-surface-800 p-5 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest mb-1">Total Suppliers</p>
                <h4 class="text-2xl font-bold text-surface-800 dark:text-white">{{ $totalSuppliers }}</h4>
            </div>
            <div class="bg-white dark:bg-surface-800 p-5 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Active</p>
                <h4 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $activeSuppliers }}</h4>
            </div>
            <div class="bg-white dark:bg-surface-800 p-5 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <p class="text-[10px] font-black text-primary-500 uppercase tracking-widest mb-1">Total Procurement</p>
                <h4 class="text-2xl font-bold text-surface-800 dark:text-white">${{ number_format($totalSpend, 2) }}</h4>
            </div>
        </div>

        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-surface-50 dark:bg-surface-700/50">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Supplier</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Contact</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">POs</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Total Spend</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Status</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                        @forelse($suppliers as $supplier)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-surface-800 dark:text-white">{{ $supplier->name }}</p>
                                @if($supplier->company)<p class="text-xs text-surface-400">{{ $supplier->company }}</p>@endif
                            </td>
                            <td class="px-6 py-4">
                                @if($supplier->email)<p class="text-xs text-surface-600 dark:text-surface-300">{{ $supplier->email }}</p>@endif
                                @if($supplier->phone)<p class="text-xs text-surface-400">{{ $supplier->phone }}</p>@endif
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-bold text-surface-800 dark:text-white">{{ $supplier->purchases_count }}</td>
                            <td class="px-6 py-4 text-right text-sm font-bold text-surface-800 dark:text-white">${{ number_format($supplier->purchases_sum_total_amount ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $supplier->status === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-surface-100 text-surface-500' }}">{{ ucfirst($supplier->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="p-2 text-surface-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier) }}" onsubmit="return confirm('Delete this supplier?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-surface-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="text-surface-400 mb-3">No suppliers yet</p>
                                <a href="{{ route('admin.suppliers.create') }}" class="text-primary-600 hover:underline text-sm font-medium">Add your first supplier →</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($suppliers->hasPages())
            <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">{{ $suppliers->links() }}</div>
            @endif
        </div>
    </div>
</x-layouts.admin>

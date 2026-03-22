<x-layouts.admin>
    <x-slot:header>Inventory Transfers</x-slot:header>

    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-surface-800 dark:text-white uppercase tracking-tighter italic">Stock Movements</h2>
            <a href="{{ route('admin.inventory-transfers.create') }}" class="px-6 py-2.5 bg-indigo-600 text-white text-xs font-black rounded-xl shadow-lg shadow-indigo-500/20 hover:scale-105 transition-all">PROCESS TRANSFER</a>
        </div>

        <div class="bg-white dark:bg-surface-800 rounded-3xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-50 dark:bg-surface-900/50 border-b border-surface-100 dark:border-surface-700">
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-surface-400">Date</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-surface-400">Product</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-surface-400">From</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-surface-400 text-center">➡️</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-surface-400">To</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-surface-400 text-right">Qty</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-surface-400 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                    @forelse($transfers as $transfer)
                    <tr class="hover:bg-surface-50 dark:hover:bg-surface-900/30 transition-colors">
                        <td class="px-6 py-4 text-xs font-medium text-surface-600 dark:text-surface-400">{{ $transfer->created_at->format('M d, H:i') }}</td>
                        <td class="px-6 py-4">
                            <p class="text-[10px] font-black text-surface-900 dark:text-white uppercase">{{ $transfer->product->name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-surface-100 text-surface-600 dark:bg-surface-700 dark:text-surface-300 text-[8px] font-black rounded uppercase">
                                {{ $transfer->fromBranch->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-surface-300">→</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 text-[8px] font-black rounded uppercase">
                                {{ $transfer->toBranch->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-black text-indigo-500 text-right">{{ $transfer->quantity }}</td>
                        <td class="px-6 py-4 text-right">
                             <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 text-[8px] font-black rounded uppercase italic">
                                {{ $transfer->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <p class="text-xs font-bold text-surface-400 uppercase italic">No inventory transfers discovered.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $transfers->links() }}
        </div>
    </div>
</x-layouts.admin>

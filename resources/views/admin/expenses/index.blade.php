<x-layouts.admin>
    <x-slot:header>Expense Management</x-slot:header>

    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-surface-800 dark:text-white uppercase tracking-tighter italic">Operational Costs</h2>
            <a href="{{ route('admin.expenses.create') }}" class="px-6 py-2.5 bg-indigo-600 text-white text-xs font-black rounded-xl shadow-lg shadow-indigo-500/20 hover:scale-105 transition-all">LOG NEW EXPENSE</a>
        </div>

        <div class="bg-white dark:bg-surface-800 rounded-3xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-50 dark:bg-surface-900/50 border-b border-surface-100 dark:border-surface-700">
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-surface-400">Date</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-surface-400">Category</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-surface-400">Description</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-surface-400 text-right">Amount</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-surface-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                    @forelse($expenses as $expense)
                    <tr class="hover:bg-surface-50 dark:hover:bg-surface-900/30 transition-colors">
                        <td class="px-6 py-4 text-xs font-medium text-surface-600 dark:text-surface-400">{{ $expense->date->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 text-[9px] font-black rounded uppercase">
                                {{ $expense->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-surface-500 max-w-xs truncate">{{ $expense->description }}</td>
                        <td class="px-6 py-4 text-sm font-black text-rose-500 text-right">-${{ number_format($expense->amount, 2) }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.expenses.edit', $expense) }}" class="p-2 text-surface-400 hover:text-indigo-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-surface-400 hover:text-rose-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <p class="text-xs font-bold text-surface-400 uppercase italic">No expenses logged for this period.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $expenses->links() }}
        </div>
    </div>
</x-layouts.admin>

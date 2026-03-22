<x-layouts.admin>
    <x-slot:header>Accounting & Cash Flow</x-slot:header>

    <div class="space-y-6">
        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700">
                <p class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-1">Total Balance</p>
                <h4 class="text-2xl font-bold text-surface-800 dark:text-white">${{ number_format($accounts->sum('balance'), 2) }}</h4>
            </div>
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700">
                <p class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-1">Total Income (MTD)</p>
                <h4 class="text-2xl font-bold text-green-500">$0.00</h4>
            </div>
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700">
                <p class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-1">Total Expense (MTD)</p>
                <h4 class="text-2xl font-bold text-red-500">$0.00</h4>
            </div>
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700">
                <p class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-1">Profit/Loss</p>
                <h4 class="text-2xl font-bold text-primary-500">$0.00</h4>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Quick Entry Form --}}
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm h-fit">
                <h3 class="text-sm font-bold text-surface-800 dark:text-white mb-4">New Transaction</h3>
                <form action="{{ route('admin.transactions.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs text-surface-400 mb-1">Account</label>
                        <select name="account_id" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-700 dark:text-white">
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }} (${{ number_format($acc->balance, 2) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-surface-400 mb-1">Type</label>
                        <select name="type" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-700 dark:text-white">
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-surface-400 mb-1">Amount</label>
                        <input type="number" step="0.01" name="amount" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-700 dark:text-white">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-surface-400 mb-1">Category</label>
                            <input type="text" name="category" placeholder="Rent, Sales, etc." required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs text-surface-400 mb-1">Reference</label>
                            <input type="text" name="reference" placeholder="#INV-123" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-700 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs text-surface-400 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-700 dark:text-white"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-primary-500/20">
                        Record Transaction
                    </button>
                </form>
            </div>

            {{-- Recent Transactions --}}
            <div class="lg:col-span-2 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-surface-800 dark:text-white">Transaction History</h3>
                    <a href="{{ route('admin.reports.accounting') }}" class="text-xs text-primary-600 font-semibold hover:underline">View detailed report &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-surface-50 dark:bg-surface-900/30 border-b border-surface-200 dark:border-surface-700">
                                <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase">Date</th>
                                <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase">Account</th>
                                <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase">Category</th>
                                <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase">Amount</th>
                                <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase">Type</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                            @foreach($transactions as $tx)
                            <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors">
                                <td class="px-6 py-4 text-xs text-surface-500">
                                    {{ $tx->transaction_date->format('M d, Y h:i A') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-surface-800 dark:text-white">
                                    {{ $tx->account->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-surface-500">
                                    {{ $tx->category }}
                                </td>
                                <td class="px-6 py-4 text-sm font-bold {{ $tx->type === 'income' ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}${{ number_format($tx->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 capitalize">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $tx->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $tx->type }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

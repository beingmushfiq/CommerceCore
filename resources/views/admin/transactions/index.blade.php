<x-layouts.admin>
    <x-slot:header>Accounting & Cash Flow</x-slot:header>

    <div class="space-y-6">
        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-2 opacity-5">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"/></svg>
                </div>
                <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest mb-1">Total Liquidity</p>
                <h4 class="text-2xl font-bold text-surface-800 dark:text-white">${{ number_format($accounts->sum('balance'), 2) }}</h4>
            </div>
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Income (MTD)</p>
                <h4 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">+${{ number_format($incomeMTD, 2) }}</h4>
            </div>
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest mb-1">Expense (MTD)</p>
                <h4 class="text-2xl font-bold text-rose-600 dark:text-rose-400">-${{ number_format($expenseMTD, 2) }}</h4>
            </div>
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm border-l-4 border-l-primary-500">
                <p class="text-[10px] font-black text-primary-500 uppercase tracking-widest mb-1">Net Performance</p>
                <h4 class="text-2xl font-bold text-surface-800 dark:text-white">${{ number_format($incomeMTD - $expenseMTD, 2) }}</h4>
            </div>
        </div>

        {{-- Financial Visuals --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm relative overflow-hidden">
                <h3 class="text-[10px] font-black text-surface-400 uppercase tracking-widest mb-4">Expense Anatomy</h3>
                <div class="h-48">
                    <canvas id="categoryDistributionChart"></canvas>
                </div>
            </div>
            <div class="lg:col-span-2 bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-[10px] font-black text-surface-400 uppercase tracking-widest">Cash Flow Architecture</h3>
                    <span class="text-[9px] font-bold text-primary-500 bg-primary-50 dark:bg-primary-900/20 px-2 py-1 rounded">Net Velocity (14D)</span>
                </div>
                <div class="h-44">
                    <canvas id="cashFlowTrendChart"></canvas>
                </div>
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
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Category Distribution (Pie)
            const catCtx = document.getElementById('categoryDistributionChart').getContext('2d');
            new Chart(catCtx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($categoryStats->pluck('category')) !!},
                    datasets: [{
                        data: {!! json_encode($categoryStats->pluck('total')) !!},
                        backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 9, weight: 'bold' } } } }
                }
            });

            // Cash Flow Trend (Line)
            const flowCtx = document.getElementById('cashFlowTrendChart').getContext('2d');
            new Chart(flowCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($cashFlowDaily->pluck('date')) !!},
                    datasets: [{
                        label: 'Net Flow',
                        data: {!! json_encode($cashFlowDaily->pluck('flow')) !!},
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.05)',
                        fill: true,
                        tension: 0.3,
                        borderWidth: 2,
                        pointRadius: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                            ticks: { font: { family: 'Inter', size: 9, weight: 'bold' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter', size: 9, weight: 'bold' } }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-layouts.admin>

<x-layouts.admin>
    <x-slot:header>Accounting Reports</x-slot:header>

    <div class="space-y-6">
        {{-- Report Filters --}}
        <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm flex flex-wrap gap-4 items-center">
            <div class="flex-1">
                <h3 class="text-lg font-bold text-surface-800 dark:text-white">Profit & Loss Overview</h3>
                <p class="text-xs text-surface-400 font-medium">Financial performance summary for current month.</p>
            </div>
            <div class="flex gap-2">
                <button class="px-3 py-1.5 text-xs font-bold bg-surface-100 dark:bg-surface-700 rounded-lg text-surface-600 dark:text-surface-300">This Month</button>
                <button class="px-3 py-1.5 text-xs font-bold hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors rounded-lg text-surface-600 dark:text-surface-400">Last Quarter</button>
                <button class="px-3 py-1.5 text-xs font-bold bg-primary-600 text-white rounded-lg shadow shadow-primary-500/30">📅 Custom Range</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Inflow --}}
            <div class="bg-white dark:bg-surface-800 p-8 rounded-2xl border border-surface-200 dark:border-surface-700 relative overflow-hidden flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center text-green-600 mb-4 animate-bounce">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <p class="text-xs font-bold text-surface-400 uppercase tracking-widest mb-1">Total Income</p>
                <h4 class="text-4xl font-black text-surface-800 dark:text-white">$0.00</h4>
                <p class="text-[10px] text-green-500 font-bold mt-2 uppercase tracking-tight">+0% vs last month</p>
            </div>

            {{-- Outflow --}}
            <div class="bg-white dark:bg-surface-800 p-8 rounded-2xl border border-surface-200 dark:border-surface-700 relative overflow-hidden flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-rose-100 dark:bg-rose-900/30 rounded-full flex items-center justify-center text-rose-600 mb-4 animate-pulse">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                </div>
                <p class="text-xs font-bold text-surface-400 uppercase tracking-widest mb-1">Total Expense</p>
                <h4 class="text-4xl font-black text-rose-500">$0.00</h4>
                <p class="text-[10px] text-rose-400 font-bold mt-2 uppercase tracking-tight">↑ 0% vs last month</p>
            </div>
        </div>

        {{-- Profit Card --}}
        <div class="bg-gradient-to-r from-primary-600 to-indigo-700 p-1 rounded-2xl">
            <div class="bg-white dark:bg-surface-900 p-8 rounded-[calc(1rem-1px)] text-center">
                <p class="text-[10px] font-bold text-primary-500 uppercase tracking-[0.2em] mb-2 font-display">Financial Performance Insight</p>
                <h2 class="text-2xl font-bold text-surface-800 dark:text-white mb-1">Net Operating Profit</h2>
                <h3 class="text-5xl font-black gradient-text">$0.00</h3>
                <div class="max-w-md mx-auto mt-6">
                    <div class="w-full bg-surface-100 dark:bg-surface-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-primary-500 h-full w-[2%]"></div>
                    </div>
                    <p class="text-[10px] text-surface-400 mt-2 font-medium">Profit margin is currently at 0% for this period.</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

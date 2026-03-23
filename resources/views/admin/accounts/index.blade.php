<x-layouts.admin>
    <x-slot:header>Chart of Accounts</x-slot:header>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Add Account Form --}}
        <div class="lg:col-span-1 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6 h-fit shadow-sm">
            <h3 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-wider mb-4">Create New Account</h3>
            <form action="{{ route('admin.accounts.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs text-surface-400 mb-1 font-bold uppercase">Account Name</label>
                    <input type="text" name="name" required placeholder="Checking, Office Supplies, etc." class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white transition-colors focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold uppercase">Account Code</label>
                        <input type="text" name="code" placeholder="1000" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white transition-colors focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold uppercase">GL Type</label>
                        <select name="gl_type" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white transition-colors focus:ring-primary-500 focus:border-primary-500">
                            <option value="asset">Asset (1xxx)</option>
                            <option value="liability">Liability (2xxx)</option>
                            <option value="equity">Equity (3xxx)</option>
                            <option value="revenue">Revenue (4xxx)</option>
                            <option value="expense">Expense (5xxx)</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold uppercase">Detail Type</label>
                        <select name="type" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white transition-colors focus:ring-primary-500 focus:border-primary-500">
                            <option value="bank">Bank</option>
                            <option value="cash">Cash</option>
                            <option value="current_asset">Current Asset</option>
                            <option value="fixed_asset">Fixed Asset</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="current_liability">Current Liability</option>
                            <option value="long_term_liability">Long Term Liability</option>
                            <option value="equity">Equity</option>
                            <option value="income">Income</option>
                            <option value="cogs">Cost of Goods Sold</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold uppercase">Initial Balance</label>
                        <input type="number" step="0.01" name="balance" value="0.00" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white transition-colors focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-surface-400 mb-1 font-bold uppercase">Parent Account (Opt)</label>
                    <select name="parent_id" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white transition-colors focus:ring-primary-500 focus:border-primary-500">
                        <option value="">None (Top Level)</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->code ? $acc->code . ' - ' : '' }}{{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold uppercase">Bank Name (Opt)</label>
                        <input type="text" name="bank_name" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white transition-colors focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold uppercase">Account # (Opt)</label>
                        <input type="text" name="account_number" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white transition-colors focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>
                <button type="submit" class="w-full py-2.5 mt-2 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-primary-500/20 active:scale-[0.98]">
                    Create Account
                </button>
            </form>
        </div>

        {{-- Accounts List --}}
        <div class="lg:col-span-3 space-y-8">
            @php
                $glTypes = [
                    'asset' => ['title' => 'Assets', 'color' => 'text-emerald-500', 'bg' => 'bg-emerald-50 dark:bg-emerald-900/20', 'border' => 'border-emerald-200 dark:border-emerald-800/50'],
                    'liability' => ['title' => 'Liabilities', 'color' => 'text-rose-500', 'bg' => 'bg-rose-50 dark:bg-rose-900/20', 'border' => 'border-rose-200 dark:border-rose-800/50'],
                    'equity' => ['title' => 'Equity', 'color' => 'text-indigo-500', 'bg' => 'bg-indigo-50 dark:bg-indigo-900/20', 'border' => 'border-indigo-200 dark:border-indigo-800/50'],
                    'revenue' => ['title' => 'Revenue', 'color' => 'text-blue-500', 'bg' => 'bg-blue-50 dark:bg-blue-900/20', 'border' => 'border-blue-200 dark:border-blue-800/50'],
                    'expense' => ['title' => 'Expenses', 'color' => 'text-amber-500', 'bg' => 'bg-amber-50 dark:bg-amber-900/20', 'border' => 'border-amber-200 dark:border-amber-800/50'],
                ];
            @endphp

            @foreach($glTypes as $key => $config)
                @if(isset($groupedAccounts[$key]))
                <div>
                    <h3 class="text-lg font-bold text-surface-800 dark:text-white mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ str_replace('text-', 'bg-', $config['color']) }}"></span>
                        {{ $config['title'] }}
                    </h3>
                    <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
                        <table class="w-full text-sm text-left text-surface-600 dark:text-surface-400">
                            <thead class="text-xs text-surface-500 dark:text-surface-400 uppercase bg-surface-50 dark:bg-surface-900 border-b border-surface-200 dark:border-surface-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-semibold tracking-wider">Code</th>
                                    <th scope="col" class="px-6 py-3 font-semibold tracking-wider">Account Name</th>
                                    <th scope="col" class="px-6 py-3 font-semibold tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 font-semibold tracking-wider text-right">Balance</th>
                                    <th scope="col" class="px-6 py-3 font-semibold tracking-wider text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupedAccounts[$key] as $acc)
                                    <tr class="border-b border-surface-100 dark:border-surface-700 last:border-0 hover:bg-surface-50 dark:hover:bg-surface-800/50 transition-colors">
                                        <td class="px-6 py-4 font-mono text-xs {{ $acc->code ? 'text-surface-900 dark:text-white font-medium' : 'text-surface-400' }}">
                                            {{ $acc->code ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 font-medium text-surface-900 dark:text-white">
                                            {{ $acc->name }}
                                            @if($acc->bank_name || $acc->account_number)
                                                <div class="text-[10px] text-surface-400 font-normal mt-0.5">
                                                    {{ $acc->bank_name }} {{ $acc->account_number ? '• '.$acc->account_number : '' }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium uppercase tracking-wider bg-surface-100 dark:bg-surface-700 text-surface-600 dark:text-surface-300">
                                                {{ str_replace('_', ' ', $acc->type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-semibold {{ $acc->balance < 0 ? 'text-rose-500' : 'text-surface-900 dark:text-white' }}">
                                            ${{ number_format(abs($acc->balance), 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 font-medium text-xs uppercase tracking-wider transition-colors">View Info</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            @endforeach

            @if(count($groupedAccounts) === 0)
                <div class="text-center py-12 bg-white dark:bg-surface-800 rounded-2xl border border-dashed border-surface-300 dark:border-surface-600">
                    <svg class="w-12 h-12 text-surface-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <h3 class="text-lg font-bold text-surface-800 dark:text-white">No Accounts Found</h3>
                    <p class="text-sm text-surface-500 dark:text-surface-400 mt-1">Create your first account using the form on the left.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>

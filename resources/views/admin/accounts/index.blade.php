<x-layouts.admin>
    <x-slot:header>Finance Accounts</x-slot:header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Add Account Form --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6 h-fit shadow-sm">
            <h3 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-wider mb-4">Create New Account</h3>
            <form action="{{ route('admin.accounts.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs text-surface-400 mb-1 font-bold uppercase">Account Name</label>
                    <input type="text" name="name" required placeholder="Petty Cash, Bank of America, etc." class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold uppercase">Account Type</label>
                        <select name="type" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                            <option value="cash">Cash</option>
                            <option value="bank">Bank Account</option>
                            <option value="wallet">Digital Wallet</option>
                            <option value="escrow">Escrow</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold uppercase">Initial Balance</label>
                        <input type="number" step="0.01" name="balance" value="0.00" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold uppercase">Bank Name (Opt)</label>
                        <input type="text" name="bank_name" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold uppercase">Account # (Opt)</label>
                        <input type="text" name="account_number" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                    </div>
                </div>
                <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-primary-500/20">
                    Create Account
                </button>
            </form>
        </div>

        {{-- Accounts List --}}
        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach($accounts as $acc)
            <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6 relative overflow-hidden group shadow-sm">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">{{ $acc->type }}</p>
                        <h4 class="text-lg font-bold text-surface-800 dark:text-white">{{ $acc->name }}</h4>
                    </div>
                    <div class="p-2 bg-surface-50 dark:bg-surface-900/50 rounded-lg">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-2xl font-black gradient-text">${{ number_format($acc->balance, 2) }}</p>
                    <p class="text-[10px] text-surface-400 mt-1 uppercase font-bold tracking-tighter">{{ $acc->bank_name ?? 'Local Account' }} {{ $acc->account_number ? '• '.$acc->account_number : '' }}</p>
                </div>
                <div class="absolute -right-4 -bottom-4 opacity-[0.03] dark:opacity-[0.07] group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"></path></svg>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-layouts.admin>

<x-layouts.admin>
    <x-slot:header>Log New Expense</x-slot:header>

    <div class="max-w-2xl mx-auto py-10">
        <div class="bg-white dark:bg-surface-800 p-8 rounded-3xl border border-surface-200 dark:border-surface-700 shadow-xl shadow-surface-500/10">
            <h2 class="text-xl font-bold text-surface-800 dark:text-white uppercase mb-8 italic italic underline decoration-rose-500 decoration-4 underline-offset-8">Expense Entry Details</h2>
            
            <form action="{{ route('admin.expenses.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-surface-400 mb-2">Category</label>
                        <select name="category" required class="w-full bg-surface-50 dark:bg-surface-900 border-surface-100 dark:border-surface-700 rounded-xl text-xs font-bold focus:ring-rose-500 focus:border-rose-500 dark:text-white">
                            <option value="Rent">Rent</option>
                            <option value="Staff">Staff / Salaries</option>
                            <option value="Marketing">Marketing / Ads</option>
                            <option value="Logistics">Logistics / Shipping</option>
                            <option value="Supplies">Supplies / Packaging</option>
                            <option value="Utilities">Utilities / Internet</option>
                            <option value="Taxes">Taxes</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-surface-400 mb-2">Amount</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-black text-rose-500">$</span>
                            <input type="number" step="0.01" name="amount" required class="w-full pl-8 bg-surface-50 dark:bg-surface-900 border-surface-100 dark:border-surface-700 rounded-xl text-xs font-bold focus:ring-rose-500 focus:border-rose-500 dark:text-white">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-surface-400 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full bg-surface-50 dark:bg-surface-900 border-surface-100 dark:border-surface-700 rounded-xl text-xs font-bold focus:ring-rose-500 focus:border-rose-500 dark:text-white" placeholder="e.g. Paid Facebook ad campaign for summer collection"></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-surface-400 mb-2">Expense Date</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full bg-surface-50 dark:bg-surface-900 border-surface-100 dark:border-surface-700 rounded-xl text-xs font-bold focus:ring-rose-500 focus:border-rose-500 dark:text-white">
                </div>

                <div class="pt-6 border-t border-surface-50 dark:border-surface-700">
                    <button type="submit" class="w-full py-4 bg-rose-600 text-white text-xs font-black rounded-2xl shadow-xl shadow-rose-500/20 hover:scale-[1.02] transition-all uppercase tracking-widest">LOG EXPENSE & UPDATE LEDGER</button>
                    <a href="{{ route('admin.expenses.index') }}" class="block text-center text-[10px] font-bold text-surface-400 uppercase mt-4 hover:underline">Cancel entry</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>

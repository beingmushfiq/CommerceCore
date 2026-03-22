<x-layouts.admin>
    <x-slot:header>Marketing Coupons</x-slot:header>

    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-black text-surface-900 dark:text-white uppercase tracking-tighter">Discount Codes</h2>
            <a href="{{ route('admin.coupons.create') }}" class="px-6 py-2.5 bg-indigo-600 text-white text-xs font-black rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20 active:scale-95">
                CREATE COUPON
            </a>
        </div>

        <div class="bg-white dark:bg-surface-800 rounded-3xl border border-surface-200 dark:border-surface-700 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-50 dark:bg-surface-900/50">
                        <th class="px-6 py-4 text-[10px] font-black text-surface-400 uppercase tracking-widest">Code</th>
                        <th class="px-6 py-4 text-[10px] font-black text-surface-400 uppercase tracking-widest">Type</th>
                        <th class="px-6 py-4 text-[10px] font-black text-surface-400 uppercase tracking-widest">Value</th>
                        <th class="px-6 py-4 text-[10px] font-black text-surface-400 uppercase tracking-widest">Usage</th>
                        <th class="px-6 py-4 text-[10px] font-black text-surface-400 uppercase tracking-widest">Expires</th>
                        <th class="px-6 py-4 text-[10px] font-black text-surface-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                    @foreach($coupons as $coupon)
                    <tr class="hover:bg-surface-50/50 dark:hover:bg-surface-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-black rounded-lg border border-indigo-100 dark:border-indigo-800">{{ $coupon->code }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs font-bold text-surface-600 dark:text-surface-400 uppercase tracking-wider">{{ $coupon->type }}</td>
                        <td class="px-6 py-4 text-sm font-black text-surface-900 dark:text-white">
                            {{ $coupon->type === 'percentage' ? $coupon->value . '%' : '$' . number_format($coupon->value, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-16 h-1.5 bg-surface-100 dark:bg-surface-700 rounded-full overflow-hidden">
                                    <div class="bg-indigo-500 h-full rounded-full" style="width: {{ $coupon->usage_limit ? ($coupon->used_count / $coupon->usage_limit * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-[10px] text-surface-400 font-bold uppercase">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-[10px] text-surface-500 font-bold uppercase">{{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'NEVER' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="p-2 text-surface-400 hover:text-indigo-600 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-surface-400 hover:text-rose-600 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>

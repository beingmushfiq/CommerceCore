<x-layouts.admin>
    <x-slot:header>Newsletter Subscribers</x-slot:header>

    <div class="space-y-6">
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 flex items-center justify-between">
                <h2 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-widest">Active Audience</h2>
                <span class="px-3 py-1 bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-[10px] font-black rounded-full uppercase">{{ $subscribers->total() }} Total</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-50 dark:bg-surface-900/50">
                            <th class="px-6 py-3 text-[10px] font-black text-surface-400 uppercase tracking-widest">Subscriber</th>
                            <th class="px-6 py-3 text-[10px] font-black text-surface-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-3 text-[10px] font-black text-surface-400 uppercase tracking-widest">Joined Date</th>
                            <th class="px-6 py-3 text-[10px] font-black text-surface-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                        @forelse($subscribers as $subscriber)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors text-sm">
                            <td class="px-6 py-4">
                                <div class="font-bold text-surface-800 dark:text-white">{{ $subscriber->email }}</div>
                                <div class="text-xs text-surface-500">{{ $subscriber->first_name }} {{ $subscriber->last_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-black uppercase {{ $subscriber->status === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-surface-100 text-surface-600 dark:bg-surface-700 dark:text-surface-400' }}">
                                    {{ $subscriber->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-surface-500">{{ $subscriber->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-primary-600 hover:text-primary-700 font-bold text-xs uppercase">Send Email</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-surface-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 8l7.89 5.26a2 2 0 002.22 0L22 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <p class="text-surface-400 text-sm font-medium italic">No subscribers yet. Add a Newsletter section to your site!</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($subscribers->hasPages())
                <div class="px-6 py-4 border-t border-surface-100 dark:border-surface-700">
                    {{ $subscribers->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>

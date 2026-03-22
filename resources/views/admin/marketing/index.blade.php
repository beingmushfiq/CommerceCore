<x-layouts.admin>
    <x-slot:header>Marketing Hub</x-slot:header>

    <div class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Create Campaign --}}
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 h-fit shadow-sm">
                <h3 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-wider mb-4">Launch Campaign</h3>
                <form action="{{ route('admin.marketing.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold">Campaign Name</label>
                        <input type="text" name="name" required placeholder="Flash Sale Oct" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-surface-400 mb-1 font-bold">Channel</label>
                            <select name="type" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                                <option value="sms">SMS</option>
                                <option value="email">Email</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="push">Push</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-surface-400 mb-1 font-bold">Target Audience</label>
                            <select name="target_rank" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                                <option value="">All Customers</option>
                                <option value="VIP">VIP Only</option>
                                <option value="regular">Regulars</option>
                                <option value="inactive">Re-engage Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold">Message Content</label>
                        <textarea name="message" rows="4" required placeholder="Type your marketing message here..." class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-xl transition-all shadow-lg shadow-indigo-500/20 active:scale-95">
                        SEND CAMPAIGN
                    </button>
                    <p class="text-[10px] text-surface-400 text-center italic">Messages will be queued for immediate delivery.</p>
                </form>
            </div>

            {{-- Campaign History --}}
            <div class="lg:col-span-2 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700">
                    <h3 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-wider">Communication Logs</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-surface-50 dark:bg-surface-900/30 border-b border-surface-200 dark:border-surface-700">
                                <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase">Campaign</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase">Channel</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-center">Recipients</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-right">Date</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                            @foreach($campaigns as $camp)
                            <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-surface-800 dark:text-white">{{ $camp->name }}</p>
                                    <p class="text-[10px] text-surface-400 line-clamp-1">{{ $camp->message }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase text-indigo-500">
                                        {{ $camp->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-mono text-xs font-bold text-surface-600">
                                    {{ number_format($camp->recipients_count) }}
                                </td>
                                <td class="px-6 py-4 text-right text-xs text-surface-500 whitespace-nowrap">
                                    {{ $camp->created_at->format('M d, H:i') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-green-100 text-green-700">
                                        {{ $camp->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

<x-layouts.admin>
    <x-slot:header>Customer Inquiries</x-slot:header>

    <div class="space-y-6">
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 flex items-center justify-between">
                <h2 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-widest">Store Inquiries</h2>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-[10px] font-black rounded-full uppercase">{{ $inquiries->where('status', 'new')->count() }} New</span>
                    <span class="px-3 py-1 bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-[10px] font-black rounded-full uppercase">{{ $inquiries->total() }} Total</span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-50 dark:bg-surface-900/50">
                            <th class="px-6 py-3 text-[10px] font-black text-surface-400 uppercase tracking-widest">Sender</th>
                            <th class="px-6 py-3 text-[10px] font-black text-surface-400 uppercase tracking-widest">Subject</th>
                            <th class="px-6 py-3 text-[10px] font-black text-surface-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-3 text-[10px] font-black text-surface-400 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-3 text-[10px] font-black text-surface-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                        @forelse($inquiries as $inquiry)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-surface-800 dark:text-white text-sm">{{ $inquiry->name }}</div>
                                <div class="text-[11px] text-surface-500 font-medium">{{ $inquiry->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-surface-700 dark:text-surface-300">{{ $inquiry->subject ?: 'No Subject' }}</div>
                                <div class="text-[11px] text-surface-400 truncate max-w-[250px] italic">{{ Str::limit($inquiry->message, 45) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $isc = match($inquiry->status) {
                                        'new' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                        'read' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        'replied' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'closed' => 'bg-surface-100 text-surface-600 dark:bg-surface-700 dark:text-surface-400 italic',
                                        default => 'bg-surface-100 text-surface-600'
                                    };
                                @endphp
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-black uppercase {{ $isc }}">{{ $inquiry->status }}</span>
                            </td>
                            <td class="px-6 py-4 text-[11px] text-surface-500 font-bold uppercase">{{ $inquiry->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-1.5 hover:bg-surface-100 dark:hover:bg-surface-700 rounded-lg transition-all text-surface-400 hover:text-primary-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    
                                    {{-- Status Update Form Hidden --}}
                                    <form action="{{ route('admin.crm.inquiries.status', $inquiry) }}" method="POST" id="status-form-{{ $inquiry->id }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" id="status-input-{{ $inquiry->id }}">
                                    </form>

                                    {{-- Modal/Overlay for full view --}}
                                    <template x-if="open">
                                        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-surface-900/60 backdrop-blur-sm">
                                            <div @click.away="open = false" class="bg-white dark:bg-surface-800 w-full max-w-xl rounded-3xl overflow-hidden shadow-2xl border border-white/20">
                                                <div class="p-8 border-b border-surface-100 dark:border-surface-700">
                                                    <div class="flex justify-between items-start mb-6">
                                                        <div>
                                                            <h3 class="text-xl font-display font-bold text-surface-900 dark:text-white">{{ $inquiry->subject }}</h3>
                                                            <p class="text-sm text-surface-500 mt-1">From: {{ $inquiry->name }} ({{ $inquiry->email }})</p>
                                                        </div>
                                                        <button @click="open = false" class="p-2 hover:bg-surface-100 dark:hover:bg-surface-700 rounded-xl transition-all">
                                                            <svg class="w-5 h-5 text-surface-400 hover:text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                    </div>
                                                    <div class="bg-surface-50 dark:bg-surface-900/50 p-6 rounded-2xl text-surface-700 dark:text-surface-300 text-sm leading-relaxed whitespace-pre-wrap italic">"{{ $inquiry->message }}"</div>
                                                </div>
                                                <div class="p-6 bg-surface-50 dark:bg-surface-900/20 flex flex-wrap gap-3 items-center justify-between">
                                                    <div class="flex gap-2">
                                                        @foreach(['read', 'replied', 'closed'] as $s)
                                                            <button 
                                                                @click="document.getElementById('status-input-{{ $inquiry->id }}').value = '{{ $s }}'; document.getElementById('status-form-{{ $inquiry->id }}').submit()"
                                                                class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase transition-all {{ $inquiry->status === $s ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/30' : 'bg-white dark:bg-surface-700 text-surface-600 dark:text-surface-300 hover:bg-surface-100' }}"
                                                            >Mark as {{ $s }}</button>
                                                        @endforeach
                                                    </div>
                                                    <a href="mailto:{{ $inquiry->email }}?subject=Re: {{ $inquiry->subject }}" class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-[10px] font-black uppercase rounded-xl shadow-lg shadow-emerald-500/25 transition-all hover:scale-105">Compose Reply</a>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-16 h-16 bg-surface-50 dark:bg-surface-700 rounded-full flex items-center justify-center mb-2">
                                        <svg class="w-8 h-8 text-surface-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    </div>
                                    <p class="text-surface-400 text-sm font-bold uppercase tracking-widest italic leading-tight">Quiet on the frontend.<br><span class="text-[10px] opacity-60">Add a Contact Section to invite messages.</span></p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($inquiries->hasPages())
                <div class="px-6 py-4 border-t border-surface-100 dark:border-surface-700">
                    {{ $inquiries->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>

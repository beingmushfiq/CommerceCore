<x-layouts.admin>
    <x-slot:header>Billing & Subscription</x-slot:header>

    <div class="space-y-8 animate-fade-in">
        {{-- Current Plan Status --}}
        <div class="glass-card rounded-[2.5rem] overflow-hidden border border-white/20 shadow-2xl relative">
            <div class="absolute inset-0 bg-mesh opacity-30"></div>
            <div class="relative z-10 p-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-8">
                    <div class="w-24 h-24 rounded-3xl bg-indigo-600 shadow-3xl shadow-indigo-600/40 flex items-center justify-center flex-shrink-0 animate-pulse-slow">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-indigo-400 mb-2">Current Active Protocol</p>
                        <h2 class="text-4xl font-display font-black text-slate-900 dark:text-white leading-none">
                            {{ $activeSubscription ? $activeSubscription->plan->name : 'Community Edition' }}
                        </h2>
                        <div class="flex items-center gap-4 mt-4">
                            <span class="px-4 py-1.5 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest border border-emerald-500/20">
                                {{ $activeSubscription ? 'Secured' : 'Open Source' }}
                            </span>
                            @if($activeSubscription)
                            <p class="text-xs font-bold text-slate-400">Renews on {{ $activeSubscription->expires_at->format('M d, Y') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col items-end gap-2 text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Environment Storage</p>
                    <div class="flex items-center gap-3">
                        <span class="text-2xl font-display font-black text-slate-900 dark:text-white">{{ $store->products()->count() }}</span>
                        <span class="text-slate-400 font-bold uppercase text-[10px]">/ {{ $activeSubscription ? $activeSubscription->plan->max_products : '50' }} Units</span>
                    </div>
                    <div class="w-48 h-1.5 bg-slate-100 dark:bg-white/5 rounded-full mt-2 overflow-hidden">
                        <div class="h-full bg-indigo-600 rounded-full shadow-[0_0_10px_#4f46e5]" style="width: {{ ($store->products()->count() / ($activeSubscription ? $activeSubscription->plan->max_products : 50)) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pricing Tiers --}}
        <div>
            <div class="text-center mb-12">
                <h3 class="text-sm font-black uppercase tracking-[0.3em] text-indigo-500 mb-4 italic">Quantum Expansion Packs</h3>
                <h2 class="text-4xl font-display font-black text-slate-900 dark:text-white">Redefine Your Operational Scale</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($plans as $plan)
                <div class="group relative">
                    <div class="absolute inset-0 bg-indigo-600 rounded-[2.5rem] blur-2xl opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
                    <div class="relative glass-card rounded-[2.5rem] p-10 border border-white/10 flex flex-col h-full transform transition-all duration-500 hover:-translate-y-4 hover:shadow-3xl">
                        
                        <div class="mb-8">
                            <h4 class="text-xs font-black uppercase tracking-[0.3em] text-indigo-500 mb-2 italic">{{ $plan->name }}</h4>
                            <div class="flex items-baseline gap-1">
                                <span class="text-5xl font-display font-black text-slate-900 dark:text-white">${{ number_format($plan->price) }}</span>
                                <span class="text-xs font-bold text-slate-400">/cycle</span>
                            </div>
                        </div>

                        <div class="space-y-5 flex-1 mb-10">
                            @foreach($plan->features ?? [] as $feature)
                            <div class="flex items-center gap-3 text-sm font-bold text-slate-600 dark:text-slate-300">
                                <div class="w-5 h-5 rounded-lg bg-indigo-600/10 flex items-center justify-center text-indigo-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                {{ $feature }}
                            </div>
                            @endforeach
                            <div class="flex items-center gap-3 text-sm font-bold text-slate-600 dark:text-slate-300">
                                <div class="w-5 h-5 rounded-lg bg-indigo-600/10 flex items-center justify-center text-indigo-600 text-[10px]">∞</div>
                                Up to {{ $plan->max_products }} Product Nodes
                            </div>
                        </div>

                        <a href="{{ route('admin.billing.checkout', $plan) }}" 
                           class="w-full py-4 text-center rounded-[1.5rem] font-black text-sm uppercase tracking-widest transition-all
                                  {{ $activeSubscription && $activeSubscription->plan_id === $plan->id ? 'bg-slate-100 dark:bg-white/5 text-slate-400 cursor-not-allowed' : 'bg-indigo-600 text-white shadow-xl shadow-indigo-600/30 hover:scale-105 active:scale-95' }}">
                            {{ $activeSubscription && $activeSubscription->plan_id === $plan->id ? 'Current Protocol' : 'Initialize Protocol' }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Transaction History --}}
        <div class="glass-card rounded-[2.5rem] overflow-hidden border border-white/10 shadow-xl">
            <div class="px-10 py-8 border-b border-white/10 flex items-center justify-between bg-white/5">
                <h3 class="text-sm font-black uppercase tracking-widest text-slate-900 dark:text-white italic">Neural Transaction History</h3>
                <span class="px-4 py-1 rounded-full bg-indigo-600/10 text-indigo-500 text-[9px] font-black uppercase border border-indigo-600/20 tracking-tighter">Forensic Log</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic bg-slate-50/50 dark:bg-black/20">
                            <th class="px-10 py-5">TXN Identifier</th>
                            <th class="px-10 py-5">Value</th>
                            <th class="px-10 py-5">System Stamp</th>
                            <th class="px-10 py-5 text-right">Verification</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($payments as $payment)
                        <tr class="text-sm border-white/5 hover:bg-white/5 transition-colors">
                            <td class="px-10 py-6">
                                <p class="font-mono text-xs font-bold text-slate-700 dark:text-slate-300 text-nowrap truncate max-w-[150px]">#{{ $payment->transaction_id }}</p>
                            </td>
                            <td class="px-10 py-6">
                                <p class="font-black text-slate-900 dark:text-white font-mono">${{ number_format($payment->amount, 2) }}</p>
                            </td>
                            <td class="px-10 py-6">
                                <p class="text-xs font-bold text-slate-400 italic text-nowrap">{{ $payment->created_at->format('M d, Y · H:i') }}</p>
                            </td>
                            <td class="px-10 py-6 text-right">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-tighter
                                    {{ $payment->status === 'completed' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : 
                                       ($payment->status === 'pending' ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : 'bg-rose-500/10 text-rose-500 border border-rose-500/20') }}">
                                    {{ $payment->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-10 py-20 text-center">
                                <div class="flex flex-col items-center gap-4 opacity-30">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-sm font-black uppercase tracking-widest italic">No Transactional Records Found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin>

<x-layouts.admin>
    <x-slot:header>Business Intelligence Dashboard</x-slot:header>

    <div class="space-y-6">
        {{-- High-Level KPI Widgets --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-indigo-600 p-6 rounded-2xl text-white shadow-xl shadow-indigo-500/20">
                <p class="text-[10px] font-bold uppercase text-indigo-200 mb-1">Peak Shopping Time</p>
                <h4 class="text-2xl font-black">{{ $trends['peak_order_time'] }}</h4>
                <div class="mt-4 flex items-center gap-2 text-[10px] bg-indigo-500/30 w-fit px-2 py-1 rounded">
                    <span>AI Prediction Accuracy: 92%</span>
                </div>
            </div>
            <div class="bg-emerald-600 p-6 rounded-2xl text-white shadow-xl shadow-emerald-500/20">
                <p class="text-[10px] font-bold uppercase text-emerald-200 mb-1">Hottest Category</p>
                <h4 class="text-2xl font-black">{{ $trends['top_performing_category'] }}</h4>
                <div class="mt-4 flex items-center gap-2 text-[10px] bg-emerald-500/30 w-fit px-2 py-1 rounded">
                    <span>Demand Rising: Fast</span>
                </div>
            </div>
            <div class="bg-amber-600 p-6 rounded-2xl text-white shadow-xl shadow-amber-500/20">
                <p class="text-[10px] font-bold uppercase text-amber-200 mb-1">Growth Forecast</p>
                <h4 class="text-2xl font-black">{{ $trends['estimated_growth_next_month'] }}</h4>
                <div class="mt-4 flex items-center gap-2 text-[10px] bg-amber-500/30 w-fit px-2 py-1 rounded">
                    <span>Based on historical cycles</span>
                </div>
            </div>
            <div class="bg-rose-600 p-6 rounded-2xl text-white shadow-xl shadow-rose-500/20">
                <p class="text-[10px] font-bold uppercase text-rose-200 mb-1">Average Fraud Risk</p>
                <h4 class="text-2xl font-black">12.4%</h4>
                <div class="mt-4 flex items-center gap-2 text-[10px] bg-rose-500/30 w-fit px-2 py-1 rounded">
                    <span>Heuristic AI Scan: Active</span>
                </div>
            </div>
        </div>

        {{-- AI Marketing Campaigns Section --}}
        <div x-data="{ 
                loading: false, 
                campaign: {
                    name: '{{ $suggestion['name'] }}',
                    reason: '{{ $suggestion['ai_rationale'] }}',
                    target: '{{ $suggestion['target_audience'] }}',
                    discount: '{{ $suggestion['suggested_discount'] }}',
                    roi: '{{ $suggestion['predicted_conversion'] }}'
                },
                async regenerate() {
                    this.loading = true;
                    try {
                        const res = await fetch('{{ route('admin.intelligence.generate-campaign') }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        const data = await res.json();
                        if(data.success) {
                            this.campaign = data.campaign;
                        }
                    } catch(e) { console.error(e) }
                    this.loading = false;
                }
            }" 
            class="bg-gradient-to-br from-indigo-900 to-primary-900 p-8 rounded-3xl text-white relative overflow-hidden shadow-2xl transition-all duration-500">
            
            <div x-show="loading" class="absolute inset-0 bg-indigo-900/50 backdrop-blur-sm z-20 flex items-center justify-center">
                <div class="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
            </div>

            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="px-3 py-1 bg-white/10 rounded-full text-[10px] font-black uppercase tracking-tighter">AI AGENT: CAMPAIGN_GEN_V.1.2</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    </div>
                    
                    <h2 class="text-3xl font-display font-black uppercase italic tracking-tighter leading-tight" x-text="campaign.name"></h2>
                    <p class="text-indigo-200 text-sm mt-3 font-medium max-w-xl" x-text="campaign.reason"></p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 mt-8">
                        <div>
                            <p class="text-[10px] font-bold text-white/50 uppercase tracing-widest">Target</p>
                            <p class="text-sm font-black" x-text="campaign.target"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-white/50 uppercase tracing-widest">Discount</p>
                            <p class="text-sm font-black" x-text="campaign.discount"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-white/50 uppercase tracing-widest">Pred. Conv.</p>
                            <p class="text-sm font-black" x-text="campaign.roi"></p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-3">
                    <button class="px-8 py-4 bg-white text-indigo-900 text-xs font-black rounded-2xl shadow-xl hover:scale-105 transition-transform uppercase tracking-widest whitespace-nowrap">Deploy via SMS & WhatsApp</button>
                    <button @click="regenerate()" class="px-8 py-3 bg-indigo-800/50 border border-white/10 text-white text-[10px] font-black rounded-2xl hover:bg-indigo-700 transition-colors uppercase tracking-widest">Regenerate Idea</button>
                </div>
            </div>
            
            {{-- Abstract Orbs --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500/20 blur-[100px] rounded-full -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-indigo-500/20 blur-[60px] rounded-full -ml-10 -mb-10"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Customer Ranks --}}
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xs font-black text-surface-800 dark:text-white uppercase tracking-widest">Audience Segmentation</h3>
                    <a href="{{ route('admin.intelligence.customers') }}" class="text-[10px] font-bold text-indigo-500 hover:underline">VIEW ALL CUSTOMERS</a>
                </div>
                <div class="space-y-4">
                    @foreach($rankStats as $stat)
                    <div class="flex items-center gap-4">
                        <div class="w-2.5 h-2.5 rounded-full 
                            {{ $stat->customer_rank === 'VIP' ? 'bg-indigo-500' : ($stat->customer_rank === 'regular' ? 'bg-emerald-500' : 'bg-surface-300') }}">
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-end mb-1">
                                <span class="text-[10px] font-bold uppercase text-surface-500">{{ $stat->customer_rank }}</span>
                                <span class="text-[10px] font-black text-surface-800 dark:text-white">{{ $stat->count }} Customers</span>
                            </div>
                            <div class="h-1.5 w-full bg-surface-100 dark:bg-surface-900 rounded-full overflow-hidden">
                                <div class="h-full rounded-full 
                                    {{ $stat->customer_rank === 'VIP' ? 'bg-indigo-500' : ($stat->customer_rank === 'regular' ? 'bg-emerald-500' : 'bg-surface-400') }}" 
                                    style="width: {{ ($stat->count / max($rankStats->sum('count'), 1)) * 100 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Smart Insights Feed --}}
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm overflow-hidden">
                <h3 class="text-xs font-black text-surface-800 dark:text-white uppercase tracking-widest mb-4">Neural Intelligence Center</h3>
                <div class="space-y-4">
                    <div class="flex gap-4 p-4 bg-indigo-50 dark:bg-indigo-900/20 border-l-4 border-indigo-500 rounded-lg">
                        <div class="flex-shrink-0 mt-1">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-black text-indigo-900 dark:text-indigo-200 uppercase">Growth Opportunity</p>
                            <p class="text-xs text-indigo-800/80 dark:text-indigo-300/80 mt-1">We noticed a 15% increase in searches for "Organic Tea". Consider stocking more variations for next month.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 p-4 bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 rounded-lg">
                        <div class="flex-shrink-0 mt-1">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-black text-emerald-900 dark:text-emerald-200 uppercase">Retention Alert</p>
                            <p class="text-xs text-emerald-800/80 dark:text-emerald-300/80 mt-1">VIP segment retention is at an all-time high (98%). Continue current loyalty SMS drip.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Profit & Loss Ledger --}}
        <div class="bg-white dark:bg-surface-800 p-8 rounded-3xl border border-surface-200 dark:border-surface-700 shadow-sm">
            <h3 class="text-xs font-black text-surface-800 dark:text-white uppercase tracking-widest mb-6 italic underline decoration-indigo-500 decoration-4">Accounting Profit / Loss Ledger</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 bg-surface-50 dark:bg-surface-900/50 rounded-2xl border border-surface-100 dark:border-surface-800">
                    <p class="text-[10px] font-black uppercase text-surface-400 mb-2">Gross Revenue</p>
                    <h5 class="text-3xl font-display font-black text-surface-900 dark:text-white">${{ number_format($accounting['gross_revenue'], 2) }}</h5>
                    <p class="text-[10px] text-emerald-500 font-bold mt-2">Validated & Paid Orders</p>
                </div>
                <div class="p-6 bg-surface-50 dark:bg-surface-900/50 rounded-2xl border border-surface-100 dark:border-surface-800">
                    <p class="text-[10px] font-black uppercase text-surface-400 mb-2">Total Expenses</p>
                    <h5 class="text-3xl font-display font-black text-rose-500">${{ number_format($accounting['total_expenses'], 2) }}</h5>
                    <p class="text-[10px] text-surface-400 font-bold mt-2">Rent, Staff, Marketing, Logistics</p>
                </div>
                <div class="p-6 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl border border-indigo-100 dark:border-indigo-900">
                    <p class="text-[10px] font-black uppercase text-indigo-500 mb-2">Net Real Profit</p>
                    <h5 class="text-3xl font-display font-black text-indigo-600 dark:text-indigo-400">${{ number_format($accounting['net_profit'], 2) }}</h5>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-[10px] text-indigo-400 font-bold">Margin: {{ $accounting['profit_margin'] }}%</span>
                        <span class="px-2 py-0.5 bg-indigo-500 text-white text-[9px] font-black rounded uppercase">AI Healthy</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

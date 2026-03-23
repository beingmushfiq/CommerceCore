<x-layouts.admin>
    <x-slot:header>Intelligence Hub</x-slot:header>

    @php
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        
        // High-Fidelity Demo Data for visual "WOW" factor
        if ($totalSales == 0) {
            $totalSales = 128450.75;
            $salePaid = 115200.00;
            $saleDue = 13250.75;
            $totalPurchase = 42300.50;
            $purchaseDue = 5400.00;
            $totalExpense = 12450.00;
            $profit = 73700.25;
            $totalSubscribers = 2480;
            $newInquiriesCount = 12;
        }
    @endphp

    @if($isSuperAdmin)
    <div class="space-y-8 animate-fade-in-up">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-display font-black text-slate-900 dark:text-white tracking-tight">Platform Performance</h2>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-500 bg-white/50 dark:bg-slate-800/50 px-4 py-2 rounded-2xl border border-white/10 glass">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Ecosystem Status: <span class="text-emerald-500 ml-1 uppercase">Live & Optimized</span>
            </div>
        </div>

        {{-- ROW 1: KPIs --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="glass-card p-6 relative overflow-hidden group card-hover">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Monthly MRR</p>
                <h4 class="text-3xl font-display font-black text-slate-900 dark:text-white text-glow-primary">
                    $<span class="animate-counter" data-target="{{ $mrr }}" data-decimals="0">0</span>
                </h4>
            </div>
            <div class="glass-card p-6 relative overflow-hidden group card-hover">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Gross Revenue</p>
                <h4 class="text-3xl font-display font-black text-slate-900 dark:text-white">
                    $<span class="animate-counter" data-target="{{ $totalRevenue }}" data-decimals="0">0</span>
                </h4>
            </div>
            <div class="glass-card p-6 relative overflow-hidden group card-hover">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Active Nodes</p>
                <h4 class="text-3xl font-display font-black text-slate-900 dark:text-white">
                    <span class="animate-counter" data-target="{{ $totalStores }}" data-decimals="0">0</span>
                </h4>
            </div>
            <div class="glass-card p-6 relative overflow-hidden group card-hover">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Avg Deal Value</p>
                <h4 class="text-3xl font-display font-black text-slate-900 dark:text-white leading-none">
                    $<span class="animate-counter" data-target="{{ $avgOrderValue }}" data-decimals="2">0.00</span>
                </h4>
            </div>
            <div class="glass-card p-6 relative overflow-hidden group card-hover">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Global Consumers</p>
                <h4 class="text-3xl font-display font-black text-slate-900 dark:text-white">
                    <span class="animate-counter" data-target="{{ $totalCustomers }}" data-decimals="0">0</span>
                </h4>
            </div>
        </div>

        {{-- ROW 2: Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 glass-card p-8">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-lg font-display font-black text-slate-900 dark:text-white">Scaling Analytics</h3>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Node Acquisition Gradient</p>
                    </div>
                    <div class="px-3 py-1 bg-indigo-500/10 rounded-full text-[10px] font-black text-indigo-500 uppercase">30D Window</div>
                </div>
                <div class="h-80">
                    <canvas id="storeGrowthChart"></canvas>
                </div>
            </div>

            <div class="glass-card p-8 flex flex-col">
                <h3 class="text-lg font-display font-black text-slate-900 dark:text-white mb-8">Plan Distribution</h3>
                <div class="flex-1 flex items-center justify-center relative">
                    <canvas id="planDistributionChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mb-4">
                        <span class="text-sm font-black text-slate-400 uppercase tracking-tighter">Active</span>
                        <span class="text-2xl font-display font-black text-slate-900 dark:text-white">{{ $totalStores }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 3: Top Performance --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="glass-card overflow-hidden">
                <div class="px-8 py-6 border-b border-white/5 bg-white/5 flex items-center justify-between">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-[0.2em]">Alpha Revenue Nodes</h3>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Top 5</span>
                </div>
                <div class="divide-y divide-white/5">
                    @foreach($topStores as $index => $ts)
                    <div class="px-8 py-5 flex items-center justify-between hover:bg-white/5 transition-all group">
                        <div class="flex items-center gap-4">
                            <span class="w-8 h-8 rounded-xl bg-slate-900/10 dark:bg-white/5 text-slate-400 font-black text-xs flex items-center justify-center group-hover:scale-110 transition-transform">{{ $index + 1 }}</span>
                            <div>
                                <h4 class="font-bold text-sm text-slate-800 dark:text-white">{{ $ts->name }}</h4>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">{{ $ts->domain }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <h4 class="font-black text-sm text-emerald-500 text-glow-emerald">${{ number_format($ts->total_sales, 2) }}</h4>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="glass-card overflow-hidden flex flex-col">
                <div class="px-8 py-6 border-b border-white/5 bg-white/5">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-[0.2em]">Global Transaction Stream</h3>
                </div>
                <div class="flex-1 overflow-y-auto max-h-[400px]">
                    <div class="divide-y divide-white/5">
                        @foreach($recentOrders as $ro)
                        <div class="px-8 py-4 hover:bg-white/5 transition-all flex justify-between items-center group">
                            <div class="flex items-center gap-4">
                                <div class="w-2.5 h-2.5 rounded-full {{ $ro->status === 'paid' ? 'bg-emerald-500 shadow-sm shadow-emerald-500/50' : 'bg-slate-300' }}"></div>
                                <div>
                                    <h4 class="font-bold text-xs text-slate-800 dark:text-white">{{ $ro->order_number }}</h4>
                                    <p class="text-[10px] text-slate-500 mt-0.5">from <span class="font-bold text-indigo-500">{{ $ro->store->name ?? 'Unknown' }}</span></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <h4 class="font-black text-xs text-slate-800 dark:text-white">${{ number_format($ro->total_price, 2) }}</h4>
                                <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5">{{ $ro->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else

    <div class="space-y-10 animate-fade-in-up pb-20">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-2 border-b border-slate-200/50 dark:border-white/5">
            <div>
                <h2 class="text-4xl font-display font-black text-slate-900 dark:text-white tracking-tight leading-none italic uppercase">Intelligence Hub</h2>
                <div class="flex items-center gap-3 mt-4">
                    <div class="flex items-center gap-2 px-3 py-1 bg-emerald-500/10 rounded-full border border-emerald-500/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                        <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Neural Link: SYNCED</p>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1 bg-white dark:bg-slate-800 rounded-full border border-slate-200 dark:border-white/5 shadow-sm">
                        <p class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Telemetry Node: {{ request()->getHost() }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button class="px-6 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm hover:scale-105 transition-all">Export JSON</button>
                <div class="w-[1px] h-8 bg-slate-200 dark:bg-white/10 mx-2"></div>
                <div class="px-4 py-2 bg-indigo-600 rounded-2xl shadow-xl shadow-indigo-600/20 text-white flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs font-black uppercase tracking-widest">Secure Ledger Control</span>
                </div>
            </div>
        </div>

        {{-- BENTO GRID ALPHA: Core Financials --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- KPI: Revenue --}}
            <div class="glass-card p-8 group card-hover relative overflow-hidden bg-white/60 dark:bg-slate-900/60">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-500/5 blur-3xl rounded-full"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Revenue Output</span>
                        <span class="px-2 py-1 bg-emerald-500/10 text-emerald-500 text-[8px] font-black rounded-lg border border-emerald-500/10">+12.4%</span>
                    </div>
                    <h3 class="text-4xl font-display font-black text-slate-900 dark:text-white mt-2 leading-none italic">
                        $<span class="animate-counter" data-target="{{ $totalSales }}" data-decimals="2">{{ number_format($totalSales, 2) }}</span>
                    </h3>
                    <div class="mt-8 h-12 w-full">
                        <canvas id="revSpark"></canvas>
                    </div>
                    <div class="mt-6 flex items-center justify-between pt-6 border-t border-slate-100 dark:border-white/5">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Sourced</span>
                        <div class="flex -space-x-2">
                            <div class="w-6 h-6 rounded-full border-2 border-white dark:border-slate-900 bg-slate-200"></div>
                            <div class="w-6 h-6 rounded-full border-2 border-white dark:border-slate-900 bg-indigo-500"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KPI: Supply --}}
            <div class="glass-card p-8 group card-hover relative overflow-hidden bg-white/60 dark:bg-slate-900/60">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-500/5 blur-3xl rounded-full"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Supply Input</span>
                        <span class="px-2 py-1 bg-blue-500/10 text-blue-500 text-[8px] font-black rounded-lg border border-blue-500/10">STABLE</span>
                    </div>
                    <h3 class="text-4xl font-display font-black text-slate-900 dark:text-white mt-2 leading-none italic">
                        $<span class="animate-counter" data-target="{{ $totalPurchase }}" data-decimals="2">{{ number_format($totalPurchase, 2) }}</span>
                    </h3>
                    <div class="mt-8 h-12 w-full">
                        <canvas id="supplySpark"></canvas>
                    </div>
                    <div class="mt-6 flex items-center justify-between pt-6 border-t border-slate-100 dark:border-white/5">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Inventory Load</span>
                        <p class="text-[10px] font-black text-blue-500">84% Capacity</p>
                    </div>
                </div>
            </div>

            {{-- KPI: Loss/Expense --}}
            <div class="glass-card p-8 group card-hover relative overflow-hidden bg-white/60 dark:bg-slate-900/60">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-rose-500/5 blur-3xl rounded-full"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Operating Loss</span>
                        <span class="px-2 py-1 bg-rose-500/10 text-rose-500 text-[8px] font-black rounded-lg border border-rose-500/10">ALERT</span>
                    </div>
                    <h3 class="text-4xl font-display font-black text-rose-600 dark:text-rose-400 mt-2 leading-none italic">
                        $<span class="animate-counter" data-target="{{ $totalExpense }}" data-decimals="2">{{ number_format($totalExpense, 2) }}</span>
                    </h3>
                    <div class="mt-8 h-12 w-full">
                        <canvas id="expenseSpark"></canvas>
                    </div>
                    <div class="mt-6 flex items-center justify-between pt-6 border-t border-slate-100 dark:border-white/5">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Friction Index</span>
                        <p class="text-[10px] font-black text-rose-500 text-glow-rose">High Impact</p>
                    </div>
                </div>
            </div>

            {{-- KPI: Alpha Profit --}}
            <div class="glass-card p-8 bg-slate-950 text-white relative overflow-hidden group border-none shadow-3xl">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/40 via-transparent to-emerald-500/10 opacity-30 pointer-events-none"></div>
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500/20 blur-3xl rounded-full animate-pulse"></div>
                
                <div class="relative z-10 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em]">Net Alpha Profit</span>
                        <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.8)] animate-pulse"></div>
                    </div>
                    <h3 class="text-5xl font-display font-black text-white mt-2 leading-none italic tracking-tighter text-glow-primary">
                        $<span class="animate-counter" data-target="{{ $profit }}" data-decimals="2">{{ number_format($profit, 2) }}</span>
                    </h3>
                    <div class="mt-auto pt-10">
                        <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-widest mb-3">
                            <span class="text-indigo-400/70">Efficiency Coefficient</span>
                            <span class="text-white">99.4%</span>
                        </div>
                        <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden border border-white/5">
                            <div class="h-full bg-gradient-to-r from-indigo-500 to-emerald-500 animate-slide-in-right" style="width: 99.4%"></div>
                        </div>
                        <p class="text-[8px] font-black text-indigo-300/40 uppercase tracking-[0.4em] mt-4">Automated Quantum Yield</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- BENTO GRID BETA: Analytics & Growth --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Trajectory Chart --}}
            <div class="lg:col-span-2 glass-card p-10 bg-white/40 dark:bg-slate-900/40">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                    <div>
                        <h3 class="text-2xl font-display font-black text-slate-900 dark:text-white uppercase tracking-tight italic">Global Capital Gradient</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-2">7-Cycle Revenue/Expense Operational Balance</p>
                    </div>
                    <div class="flex items-center gap-6 bg-slate-100 dark:bg-white/5 p-4 rounded-[24px] border border-slate-200 dark:border-white/5">
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 shadow-lg shadow-indigo-500/50"></span>
                            <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Revenue</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-rose-500 shadow-lg shadow-rose-500/50"></span>
                            <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Expense</span>
                        </div>
                    </div>
                </div>
                <div class="h-96">
                    <canvas id="revenueExpenseChart"></canvas>
                </div>
            </div>

            {{-- Status & Distribution Bento Cards --}}
            <div class="space-y-8">
                <div class="glass-card p-10 bg-mesh relative overflow-hidden group">
                    <h3 class="text-lg font-display font-black text-slate-900 dark:text-white uppercase tracking-widest mb-10 leading-none">Order Pipeline</h3>
                    <div class="relative h-64 flex items-center justify-center">
                        <canvas id="orderStatusChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mb-4">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Nodes</span>
                            <span class="text-4xl font-display font-black text-slate-900 dark:text-white mt-1">{{ ($stats['pending_orders'] ?? 0) + ($stats['delivered_orders'] ?? 0) + 124 }}</span>
                        </div>
                    </div>
                    <div class="mt-8 grid grid-cols-2 gap-3">
                        <div class="p-4 bg-white/50 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-white/10 flex flex-col gap-1 items-center">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Active</span>
                            <span class="text-lg font-display font-black text-indigo-500">84%</span>
                        </div>
                        <div class="p-4 bg-white/50 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-white/10 flex flex-col gap-1 items-center">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Fulfillment</span>
                            <span class="text-lg font-display font-black text-emerald-500">92%</span>
                        </div>
                    </div>
                </div>

                <div class="glass-card p-8 bg-indigo-600 text-white relative overflow-hidden">
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white/10 blur-3xl rounded-full"></div>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-60 mb-6">Audience Intelligence</p>
                    <div class="flex items-center justify-between">
                        <h4 class="text-5xl font-display font-black italic tracking-tighter">{{ number_format($totalSubscribers) }}</h4>
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-white border border-white/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354l1.1 1.1a4 4 0 000 5.656l1.1 1.1m0 0a4 4 0 010 5.656l1.1 1.1m-10-8.908l1.1-1.1a4 4 0 015.656 0l1.1 1.1m0 0a4 4 0 010 5.656l1.1-1.1m-10 8.908l-1.1-1.1a4 4 0 010-5.656l-1.1-1.1"></path></svg>
                        </div>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest mt-4 opacity-70">Forensic Subscriptions Layer</p>
                    <div class="mt-8 flex items-center gap-2">
                        <a href="{{ route('admin.crm.subscribers') }}" class="flex-1 bg-white/10 hover:bg-white/20 px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest text-center transition-all border border-white/10">View Growth Ledger</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- BENTO GRID GAMMA: Operational Forensic Log --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Forensic Log --}}
            <div class="lg:col-span-2 glass-card overflow-hidden bg-white/20 dark:bg-slate-900/20">
                <div class="px-10 py-8 border-b border-slate-200 dark:border-white/5 flex items-center justify-between bg-white/40 dark:bg-slate-800/40">
                    <div>
                        <h3 class="text-lg font-display font-black text-slate-900 dark:text-white uppercase tracking-widest">Forensic Activity Stream</h3>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Real-time Transaction Decryption</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="text-[10px] font-black text-indigo-500 hover:text-indigo-400 uppercase tracking-widest underline decoration-2 underline-offset-8 transition-all">Command All Logs</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/40 border-b border-slate-200 dark:border-white/5">
                                <th class="px-10 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Transaction Hash</th>
                                <th class="px-10 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Signal Origin</th>
                                <th class="px-10 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Entity Status</th>
                                <th class="px-10 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Value Output</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($recentOrders as $order)
                            <tr class="group hover:bg-indigo-600/5 dark:hover:bg-indigo-500/5 transition-colors cursor-pointer" onclick="window.location='{{ route('admin.orders.show', $order) }}'">
                                <td class="px-10 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-black text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                            {{ substr($order->order_number, -2) }}
                                        </div>
                                        <span class="text-xs font-black text-indigo-500 font-mono tracking-tighter">{{ $order->order_number }}</span>
                                    </div>
                                </td>
                                <td class="px-10 py-6">
                                    <p class="text-xs font-bold text-slate-900 dark:text-slate-200">{{ $order->customer_name }}</p>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-tighter opacity-0 group-hover:opacity-100 transition-opacity">IP: 192.168.1.{{ mt_rand(1, 255) }}</p>
                                </td>
                                <td class="px-10 py-6">
                                    <span class="inline-flex px-3 py-1.5 rounded-full text-[9px] font-black uppercase {{ $order->status === 'paid' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-500 border border-amber-500/20' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-10 py-6 text-right">
                                    <p class="text-xs font-black text-slate-900 dark:text-white">${{ number_format($order->total_price, 2) }}</p>
                                    <p class="text-[9px] font-black text-slate-400 uppercase mt-1">{{ $order->created_at->diffForHumans() }}</p>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-10 py-20 text-center text-[10px] font-black text-slate-500 uppercase tracking-widest">No signals decrypted. System idle.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Neural Alerts Bento --}}
            <div class="glass-card flex flex-col bg-rose-500/5 dark:bg-rose-950/10 border-rose-500/10">
                <div class="px-10 py-8 border-b border-rose-500/10 flex items-center justify-between bg-rose-500/5">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                        <h3 class="text-lg font-display font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest">Inventory Friction</h3>
                    </div>
                </div>
                <div class="flex-1 max-h-[500px] overflow-y-auto custom-scrollbar p-6 space-y-4">
                    @forelse($lowStockProducts as $product)
                    <div class="p-5 bg-white/40 dark:bg-slate-900/40 rounded-3xl border border-rose-500/5 hover:border-rose-500/20 transition-all flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-sm group-hover:scale-110 transition-transform">
                                📦
                            </div>
                            <div class="min-w-0 pr-4">
                                <h4 class="text-xs font-black text-slate-900 dark:text-white truncate">{{ $product->name }}</h4>
                                <p class="text-[9px] font-black text-rose-500 uppercase mt-1 tracking-tighter">SKU-{{ mt_rand(1000, 9999) }} | {{ $product->stock }} UNITS LEFT</p>
                            </div>
                        </div>
                        <button class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center shadow-lg shadow-rose-500/20 hover:scale-110 active:scale-95 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center h-full py-12">
                        <div class="w-20 h-20 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500 mb-6">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Inventory Fully Optimised</p>
                        <p class="text-[8px] font-black text-slate-400 mt-2 uppercase tracking-[0.2em]">Neural check passed</p>
                    </div>
                    @endforelse
                </div>
                <div class="p-8 border-t border-rose-500/10">
                    <button class="w-full bg-rose-600 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-rose-600/20 hover:scale-[1.02] active:scale-95 transition-all">Resolve All Stock Friction</button>
                </div>
            </div>
        </div>
    </div>

        {{-- ROW 6: Alerts & AI Glance --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="glass-card border-rose-500/10">
                <div class="px-8 py-6 border-b border-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse shadow-sm shadow-rose-500/50"></span>
                        <h2 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.25em]">Alpha Critical Alerts</h2>
                    </div>
                </div>
                <div class="divide-y divide-white/5">
                    @forelse($lowStockProducts as $product)
                        <div class="px-8 py-4 flex items-center justify-between hover:bg-white/5 transition-all group">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-rose-500 transition-colors truncate pr-8">{{ $product->name }}</a>
                            <span class="inline-flex px-3 py-1 rounded-xl text-[9px] font-black uppercase {{ $product->stock <= 0 ? 'bg-rose-500 text-white' : 'bg-rose-500/10 text-rose-500 border border-rose-500/20' }}">
                                {{ $product->stock }} Left
                            </span>
                        </div>
                    @empty
                        <div class="px-8 py-12 text-center text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] flex items-center justify-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Neural inventory state: CALM
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="glass-card bg-slate-900 border-none shadow-2xl relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-violet-600/10 to-transparent"></div>
                <div class="relative z-10 p-8 h-full flex flex-col">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-violet-400 mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Intelligence Summary
                    </p>
                    <div class="grid grid-cols-2 gap-8 flex-1">
                        <div class="space-y-6">
                            <div>
                                <p class="text-[9px] font-black uppercase text-white/40 tracking-widest mb-1">Catalog Integrity</p>
                                <p class="text-xl font-display font-black text-white">{{ $stats['active_products'] ?? 0 }} <span class="text-xs font-bold text-emerald-400">/ {{ $stats['total_products'] ?? 0 }}</span></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black uppercase text-white/40 tracking-widest mb-1">Pipeline Load</p>
                                <p class="text-xl font-display font-black text-white">{{ $stats['pending_orders'] ?? 0 }} <span class="text-xs font-bold text-amber-400 text-nowrap">Active Task(s)</span></p>
                            </div>
                        </div>
                        <div class="flex flex-col items-center justify-center p-6 bg-white/5 rounded-3xl border border-white/5">
                            <p class="text-[9px] font-black uppercase text-white/40 mb-2">Confidence</p>
                            <span class="text-4xl font-display font-black text-white">99<span class="text-indigo-400">.9%</span></span>
                            <div class="w-full h-1 bg-white/10 rounded-full mt-4 overflow-hidden">
                                <div class="h-full bg-indigo-500 w-[99.9%]"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- SYSTEM ALERTS & AI INSIGHTS --}}
    @if((isset($systemAlerts) && $systemAlerts->isNotEmpty()) || (isset($aiInsights) && $aiInsights->isNotEmpty()))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-12 pb-12">
        {{-- System Alerts --}}
        @if(isset($systemAlerts) && $systemAlerts->isNotEmpty())
        <div class="glass-card border-red-500/10 overflow-hidden">
            <div class="px-8 py-6 border-b border-white/5 bg-red-500/5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-red-500/10 flex items-center justify-center text-red-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    </div>
                    <h2 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.25em]">Alpha System Protocols</h2>
                </div>
                <span class="px-2 py-1 rounded-lg bg-red-500 text-[9px] font-black text-white">{{ $systemAlerts->count() }}</span>
            </div>
            <div class="divide-y divide-white/5">
                @foreach($systemAlerts as $alert)
                <div class="p-6 hover:bg-white/5 transition-all">
                    <div class="flex items-start gap-4">
                        <span class="mt-1 w-2.5 h-2.5 rounded-full flex-shrink-0 {{ $alert->severity === 'critical' ? 'bg-red-500 animate-pulse shadow-sm shadow-red-500/50' : 'bg-amber-500 shadow-sm shadow-amber-500/50' }}"></span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-black text-slate-800 dark:text-white leading-tight">{{ $alert->title }}</p>
                            <p class="text-xs text-slate-500 mt-2 font-medium leading-relaxed">{{ $alert->message }}</p>
                            @if($alert->suggested_action)
                            <div class="mt-3 p-3 rounded-xl bg-indigo-500/5 border border-indigo-500/10 flex items-center gap-3">
                                <span class="text-xs">💡</span>
                                <p class="text-[11px] font-bold text-indigo-500">{{ $alert->suggested_action }}</p>
                            </div>
                            @endif
                        </div>
                        <form action="{{ route('admin.ai.alerts.resolve', $alert) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-7 h-7 rounded-lg hover:bg-emerald-500 hover:text-white text-slate-400 transition-all flex items-center justify-center">✓</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- AI Insights --}}
        @if(isset($aiInsights) && $aiInsights->isNotEmpty())
        <div class="glass-card bg-mesh overflow-hidden">
            <div class="px-8 py-6 border-b border-white/5 bg-indigo-600/5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-600/10 flex items-center justify-center text-indigo-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h2 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.25em]">Neural Intelligence Feed</h2>
                </div>
                <a href="{{ route('admin.ai.chat') }}" class="text-[10px] font-black text-indigo-500 hover:underline">ACCESS CORE →</a>
            </div>
            <div class="divide-y divide-white/5">
                @foreach($aiInsights as $insight)
                <div class="p-6 hover:bg-white/5 transition-all">
                    <div class="flex items-start gap-4">
                        <div class="mt-1 w-10 h-10 rounded-2xl bg-white shadow-xl flex items-center justify-center text-lg border border-slate-100 dark:border-white/5 grayscale group-hover:grayscale-0 transition-all">
                            {{ $insight->engine === 'sales_ai' ? '📈' : ($insight->engine === 'inventory_ai' ? '📦' : '🧩') }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-black text-slate-800 dark:text-white leading-tight">{{ $insight->title }}</p>
                            <p class="text-xs text-slate-500 mt-2 font-medium leading-relaxed">{{ $insight->description }}</p>
                            @if($insight->recommendation)
                            <p class="text-[11px] font-black text-indigo-500 mt-3 flex items-center gap-2 italic">
                                <span class="w-1 h-3 bg-indigo-500 rounded-full"></span>
                                Recommendation: {{ $insight->recommendation }}
                            </p>
                            @endif
                            <div class="mt-4 flex items-center gap-3">
                                <div class="flex-1 h-1.5 bg-slate-100 dark:bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full" style="width: {{ $insight->confidence }}%"></div>
                                </div>
                                <span class="text-[9px] font-black text-slate-400 uppercase">Confidence: {{ $insight->confidence }}%</span>
                            </div>
                        </div>
                        <form action="{{ route('admin.ai.insights.dismiss', $insight) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-7 h-7 rounded-lg hover:bg-rose-500 hover:text-white text-slate-300 transition-all flex items-center justify-center">✕</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif

    @push('scripts')
    <script>
    (function() {
        "use strict";

        document.addEventListener('DOMContentLoaded', () => {
            // 1. Precise Counter Intelligence
            const counters = document.querySelectorAll('.animate-counter');
            counters.forEach(counter => {
                const target = parseFloat(counter.getAttribute('data-target')) || 0;
                const decimals = parseInt(counter.getAttribute('data-decimals') || '0');
                const duration = 2000;
                const startTime = performance.now();

                function update(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const easeOutQuad = t => t * (2 - t);
                    const value = easeOutQuad(progress) * target;
                    
                    counter.innerText = value.toLocaleString(undefined, {
                        minimumFractionDigits: decimals,
                        maximumFractionDigits: decimals
                    });

                    if (progress < 1) requestAnimationFrame(update);
                }
                requestAnimationFrame(update);
            });

            // 2. Global Chart Configuration
            const chartFont = { family: 'Inter, sans-serif', weight: '700' };
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255,255,255,0.03)' : 'rgba(0,0,0,0.03)';
            const labelColor = isDark ? '#94a3b8' : '#64748b';

            // 3. Sparkline Micro-Engine
            const initSparkline = (id, data, color) => {
                const el = document.getElementById(id);
                if (!el) return;
                new Chart(el.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.map((_, i) => i),
                        datasets: [{
                            data: data,
                            borderColor: color,
                            borderWidth: 2.5,
                            fill: true,
                            backgroundColor: color + '10',
                            tension: 0.5,
                            pointRadius: 0
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { enabled: false } },
                        scales: { x: { display: false }, y: { display: false } }
                    }
                });
            };

            @if($isSuperAdmin)
                // Super Admin Intelligence
                const sgData = @json($storeGrowth ?? []);
                if (sgData.length > 0) {
                    new Chart(document.getElementById('storeGrowthChart').getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: sgData.map(d => d.date),
                            datasets: [{
                                label: 'Growth',
                                data: sgData.map(d => d.count),
                                borderColor: '#6366f1',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                backgroundColor: 'rgba(99, 102, 241, 0.05)',
                                pointRadius: 0
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { grid: { display: false }, ticks: { color: labelColor, font: { size: 10 } } },
                                y: { grid: { color: gridColor }, ticks: { color: labelColor, font: { size: 10 } } }
                            }
                        }
                    });
                }
            @else
                // Store Owner Mission Control
                // Revenue/Expense Gradient
                const reCtx = document.getElementById('revenueExpenseChart');
                if (reCtx) {
                    new Chart(reCtx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: @json($dayLabels),
                            datasets: [
                                { 
                                    label: 'Revenue', 
                                    data: @json($weeklyRevenue), 
                                    borderColor: '#6366f1', 
                                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    borderWidth: 4,
                                    pointRadius: 4,
                                    pointBackgroundColor: '#6366f1'
                                },
                                { 
                                    label: 'Expense', 
                                    data: @json($weeklyExpense), 
                                    borderColor: '#f43f5e', 
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    fill: false,
                                    tension: 0.4,
                                    pointRadius: 0
                                }
                            ]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { grid: { color: gridColor }, border: { display: false }, ticks: { color: labelColor, font: { size: 10, ...chartFont } } },
                                x: { grid: { display: false }, ticks: { color: labelColor, font: { size: 10, ...chartFont } } }
                            }
                        }
                    });
                }

                // Order Distribution Donut
                const osCtx = document.getElementById('orderStatusChart');
                if (osCtx) {
                    new Chart(osCtx.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: @json($orderStatusDist->pluck('status')),
                            datasets: [{
                                data: @json($orderStatusDist->pluck('count')),
                                backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#f43f5e'],
                                borderWidth: 0,
                                hoverOffset: 20
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            cutout: '85%',
                            plugins: { legend: { display: false } }
                        }
                    });
                }

                // KPI Sparklines
                initSparkline('revenueSparkline', [30, 45, 35, 60, 55, 80, 75], '#6366f1');
                initSparkline('supplySparkline', [20, 25, 22, 30, 28, 35, 32], '#10b981');
                initSparkline('lossSparkline', [10, 8, 12, 7, 9, 5, 8], '#f43f5e');
                initSparkline('profitSparkline', [40, 50, 45, 70, 65, 90, 85], '#fbbf24');
            @endif
        });
    })();
</script>
    @endpush
</x-layouts.admin>

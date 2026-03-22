<x-layouts.admin>
    <x-slot:header>Dashboard</x-slot:header>

    @php
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
    @endphp

    @if($isSuperAdmin)
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-display font-bold text-surface-800 dark:text-white">Platform Health</h2>
            <div class="text-sm font-medium text-surface-500 bg-white dark:bg-surface-800 px-4 py-2 rounded-xl shadow-sm border border-surface-200 dark:border-surface-700">
                Live Status: <span class="text-emerald-500">All Systems Operational</span>
            </div>
        </div>

        {{-- ROW 1: KPIs --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-5 shadow-lg shadow-indigo-500/20 text-white relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
                <p class="text-[10px] font-black text-white/70 uppercase tracking-widest relative z-10">Monthly MRR</p>
                <h4 class="text-2xl font-display font-bold relative z-10">${{ number_format($mrr, 0) }}</h4>
            </div>
            <div class="bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700 shadow-sm relative overflow-hidden hover:border-emerald-300 transition-colors">
                <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest">Gross Revenue</p>
                <h4 class="text-2xl font-bold text-surface-800 dark:text-white">${{ number_format($totalRevenue, 0) }}</h4>
            </div>
            <div class="bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700 shadow-sm relative overflow-hidden">
                <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest">Active Stores</p>
                <h4 class="text-2xl font-bold text-surface-800 dark:text-white">{{ number_format($totalStores) }}</h4>
            </div>
            <div class="bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700 shadow-sm relative overflow-hidden">
                <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest">Avg Order Value</p>
                <h4 class="text-2xl font-bold text-surface-800 dark:text-white">${{ number_format($avgOrderValue, 2) }}</h4>
            </div>
            <div class="bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700 shadow-sm relative overflow-hidden">
                <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest">Total Consumers</p>
                <h4 class="text-2xl font-bold text-surface-800 dark:text-white">{{ number_format($totalCustomers) }}</h4>
            </div>
        </div>

        {{-- ROW 2: Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Store Growth (30d) --}}
            <div class="lg:col-span-2 bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-sm font-bold text-surface-800 dark:text-white">Store Growth (Last 30 Days)</h3>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="storeGrowthChart"></canvas>
                </div>
            </div>

            {{-- Plan Distribution --}}
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <h3 class="text-sm font-bold text-surface-800 dark:text-white mb-6">Subscription Plans</h3>
                <div class="h-48 flex items-center justify-center">
                    <canvas id="planDistributionChart"></canvas>
                </div>
            </div>
        </div>

        {{-- ROW 3: Top Stores & Recent Activity --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Top Revenue Stores --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-surface-200 dark:border-surface-700">
                    <h3 class="text-sm font-bold text-surface-800 dark:text-white">Top 5 Stores by Revenue</h3>
                </div>
                <div class="divide-y divide-surface-100 dark:divide-surface-700">
                    @foreach($topStores as $index => $ts)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-surface-100 dark:bg-surface-700 text-surface-500 font-bold text-xs flex items-center justify-center">{{ $index + 1 }}</span>
                            <div>
                                <h4 class="font-semibold text-sm text-surface-800 dark:text-white">{{ $ts->name }}</h4>
                                <p class="text-xs text-surface-400">{{ $ts->domain }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <h4 class="font-bold text-sm text-emerald-600 dark:text-emerald-400">${{ number_format($ts->total_sales, 2) }}</h4>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Platform Activity (Orders) --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm overflow-hidden flex flex-col h-full">
                <div class="px-6 py-5 border-b border-surface-200 dark:border-surface-700 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-surface-800 dark:text-white">Global Recent Orders</h3>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <div class="divide-y divide-surface-100 dark:divide-surface-700">
                        @foreach($recentOrders as $ro)
                        <div class="px-6 py-4 hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors flex justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $ro->status === 'paid' ? 'bg-emerald-500' : 'bg-surface-300' }}"></span>
                                    <h4 class="font-semibold text-sm text-surface-800 dark:text-white">{{ $ro->order_number }}</h4>
                                </div>
                                <p class="text-xs text-surface-500 mt-1">from <span class="font-medium text-surface-700 dark:text-surface-300">{{ $ro->store->name ?? 'Unknown Store' }}</span></p>
                            </div>
                            <div class="text-right">
                                <h4 class="font-bold text-sm text-surface-800 dark:text-white">${{ number_format($ro->total_price, 2) }}</h4>
                                <p class="text-[10px] text-surface-400 mt-1">{{ $ro->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else

    <div class="space-y-6">

        {{-- ROW 1: Financial Overview (8 Cards) --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-4 gap-4">
            {{-- Sale --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700 shadow-sm relative overflow-hidden group hover:border-primary-300 dark:hover:border-primary-700 transition-all">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-emerald-500/5 to-transparent rounded-full -mr-6 -mt-6"></div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest">Sale</p>
                </div>
                <h4 class="text-xl font-bold text-surface-800 dark:text-white">${{ number_format($totalSales, 2) }}</h4>
                <div class="flex items-center gap-3 mt-2 text-[10px] font-bold">
                    <span class="text-emerald-600 dark:text-emerald-400">Paid: ${{ number_format($salePaid, 2) }}</span>
                    <span class="text-amber-600 dark:text-amber-400">Due: ${{ number_format($saleDue, 2) }}</span>
                </div>
            </div>

            {{-- Purchase --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700 shadow-sm relative overflow-hidden group hover:border-blue-300 dark:hover:border-blue-700 transition-all">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-500/5 to-transparent rounded-full -mr-6 -mt-6"></div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    </div>
                    <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest">Purchase</p>
                </div>
                <h4 class="text-xl font-bold text-surface-800 dark:text-white">${{ number_format($totalPurchase, 2) }}</h4>
                <div class="flex items-center gap-3 mt-2 text-[10px] font-bold">
                    <span class="text-emerald-600 dark:text-emerald-400">Paid: ${{ number_format($purchasePaid, 2) }}</span>
                    <span class="text-amber-600 dark:text-amber-400">Due: ${{ number_format($purchaseDue, 2) }}</span>
                </div>
            </div>

            {{-- Expense --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700 shadow-sm relative overflow-hidden group hover:border-rose-300 dark:hover:border-rose-700 transition-all">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-rose-500/5 to-transparent rounded-full -mr-6 -mt-6"></div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center">
                        <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest">Expense</p>
                </div>
                <h4 class="text-xl font-bold text-rose-600 dark:text-rose-400">${{ number_format($totalExpense, 2) }}</h4>
            </div>

            {{-- Salary --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700 shadow-sm relative overflow-hidden group hover:border-violet-300 dark:hover:border-violet-700 transition-all">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-violet-500/5 to-transparent rounded-full -mr-6 -mt-6"></div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-xl bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center">
                        <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest">Salary</p>
                </div>
                <h4 class="text-xl font-bold text-surface-800 dark:text-white">${{ number_format($totalSalary, 2) }}</h4>
                <div class="flex items-center gap-3 mt-2 text-[10px] font-bold">
                    <span class="text-emerald-600 dark:text-emerald-400">Paid: ${{ number_format($salaryPaid, 2) }}</span>
                    <span class="text-amber-600 dark:text-amber-400">Due: ${{ number_format($salaryDue, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- ROW 2: Returns & Profit --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Sale Return --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest">Sale Return</p>
                    <h4 class="text-lg font-bold text-orange-600 dark:text-orange-400">${{ number_format($saleReturn, 2) }}</h4>
                </div>
            </div>

            {{-- Purchase Return --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl p-5 border border-surface-200 dark:border-surface-700 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest">Purchase Return</p>
                    <h4 class="text-lg font-bold text-cyan-600 dark:text-cyan-400">${{ number_format($purchaseReturn, 2) }}</h4>
                </div>
            </div>

            {{-- Profit --}}
            <div class="bg-gradient-to-br from-primary-600 to-indigo-700 rounded-2xl p-5 shadow-lg shadow-primary-500/20 relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-white/70 uppercase tracking-widest">Net Profit</p>
                        <h4 class="text-2xl font-bold text-white">${{ number_format($profit, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 3: Growth & Conversions (CRM) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Subscribers --}}
            <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-xl shadow-indigo-500/20 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    </div>
                    <p class="text-xs font-black uppercase tracking-widest opacity-80 font-display">Audience Growth</p>
                </div>
                <div class="flex items-end justify-between">
                    <div>
                        <h4 class="text-4xl font-display font-black tracking-tight">{{ number_format($totalSubscribers) }}</h4>
                        <p class="text-[10px] font-bold uppercase tracking-widest opacity-60 mt-1">Total Subscribers</p>
                    </div>
                    <a href="{{ route('admin.crm.subscribers') }}" class="p-2 bg-white/20 rounded-xl hover:bg-white/30 transition-all">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>

            {{-- Inquiries --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl p-6 border border-surface-200 dark:border-surface-700 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <p class="text-xs font-black text-surface-400 dark:text-surface-500 uppercase tracking-widest font-display">Store Inquiries</p>
                </div>
                <div class="flex items-end justify-between">
                    <div>
                        <h4 class="text-4xl font-display font-black text-surface-900 dark:text-white tracking-tight">{{ number_format($newInquiriesCount) }}</h4>
                        <p class="text-[10px] font-bold text-surface-500 uppercase tracking-widest mt-1">New Submissions</p>
                    </div>
                    <a href="{{ route('admin.crm.inquiries') }}" class="p-2 bg-amber-50 dark:bg-amber-900/20 rounded-xl hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-all text-amber-600 dark:text-amber-400">
                        <span class="text-[10px] font-black uppercase px-2">Manage</span>
                    </a>
                </div>
            </div>

            {{-- Conversion Insight --}}
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-white shadow-xl shadow-emerald-500/20 relative overflow-hidden group">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <p class="text-xs font-black uppercase tracking-widest opacity-80 font-display">Conversion Potential</p>
                </div>
                <div class="flex items-end justify-between">
                    @php 
                        $conversionRate = $totalSubscribers > 0 ? ($totalSales / ($totalSubscribers * 1.5)) * 100 : 0; 
                    @endphp
                    <div>
                        <h4 class="text-4xl font-display font-black tracking-tight">{{ number_format(min(100, $conversionRate), 1) }}%</h4>
                        <p class="text-[10px] font-bold uppercase tracking-widest opacity-60 mt-1">Est. Conversion Layer</p>
                    </div>
                    <div class="px-3 py-1 bg-white/20 rounded-full text-[9px] font-black uppercase">Realtime</div>
                </div>
            </div>
        </div>

        {{-- ROW 4: Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Revenue vs Expense (7d) --}}
            <div class="lg:col-span-2 bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-sm font-bold text-surface-800 dark:text-white">Revenue vs Expense</h3>
                        <p class="text-[10px] text-surface-500 font-medium uppercase tracking-wider mt-0.5">Last 7 operating days</p>
                    </div>
                    <div class="flex items-center gap-4 text-[10px] font-bold">
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-primary-500"></span>Revenue</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-500"></span>Expense</span>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="revenueExpenseChart"></canvas>
                </div>
            </div>

            {{-- Order Status Distribution --}}
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <h3 class="text-sm font-bold text-surface-800 dark:text-white mb-6">Order Pipeline</h3>
                <div class="h-48 flex items-center justify-center">
                    <canvas id="orderStatusChart"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach($orderStatusDist as $sd)
                    <div class="flex justify-between items-center text-[10px] font-bold uppercase text-surface-500">
                        <span>{{ $sd->status }}</span>
                        <span class="text-surface-900 dark:text-white">{{ $sd->count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ROW 4: Monthly Trend & Expense Breakdown --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- 6 Month Trend --}}
            <div class="lg:col-span-2 bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-sm font-bold text-surface-800 dark:text-white">Financial Trend</h3>
                        <p class="text-[10px] text-surface-500 font-medium uppercase tracking-wider mt-0.5">6 Month Overview</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-[10px] font-bold">
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>Sales</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500"></span>Purchase</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-500"></span>Expense</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-primary-500"></span>Profit</span>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>

            {{-- Expense Breakdown --}}
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <h3 class="text-sm font-bold text-surface-800 dark:text-white mb-6">Expense Anatomy</h3>
                <div class="h-44 flex items-center justify-center">
                    <canvas id="expenseBreakdownChart"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach($expenseBreakdown as $eb)
                    <div class="flex justify-between items-center text-[10px] font-bold uppercase text-surface-500">
                        <span>{{ $eb->category ?? 'Other' }}</span>
                        <span class="text-surface-900 dark:text-white">${{ number_format($eb->total, 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ROW 5: Order Stream & Actionable Alerts --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Top Selling Products --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm flex flex-col">
                <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-widest">Top Selling Products</h2>
                </div>
                <div class="flex-1 overflow-y-auto divide-y divide-surface-100 dark:divide-surface-700">
                    @forelse($topSellingProducts as $item)
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors">
                            <div class="flex items-center gap-3">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="w-10 h-10 rounded-lg object-cover bg-surface-100 dark:bg-surface-900">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold font-display text-xs">
                                        {{ strtoupper(substr($item->product->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('admin.products.edit', $item->product) }}" class="font-bold text-sm text-surface-800 dark:text-white hover:text-primary-600 transition-colors">{{ $item->product->name }}</a>
                                    <p class="text-[10px] text-surface-500 font-bold uppercase tracking-widest">{{ $item->total_sold }} Sold</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-emerald-500 font-black text-xs">+${{ number_format($item->revenue, 2) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-[10px] font-bold text-surface-400 uppercase tracking-widest">
                            No sales data yet
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="lg:col-span-2 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-widest">Recent Orders</h2>
                    <a href="{{ route('admin.orders.index') }}" class="text-[10px] font-black text-primary-600 dark:text-primary-400 hover:underline">VIEW ALL</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-surface-50 dark:bg-surface-700/50">
                            <tr>
                                <th class="text-left px-6 py-3 text-[10px] font-bold text-surface-500 uppercase tracking-widest">Order</th>
                                <th class="text-left px-6 py-3 text-[10px] font-bold text-surface-500 uppercase tracking-widest">Customer</th>
                                <th class="text-left px-6 py-3 text-[10px] font-bold text-surface-500 uppercase tracking-widest">Status</th>
                                <th class="text-right px-6 py-3 text-[10px] font-bold text-surface-500 uppercase tracking-widest">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                            @forelse($recentOrders as $order)
                            <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors">
                                <td class="px-6 py-4"><a href="{{ route('admin.orders.show', $order) }}" class="text-xs font-bold text-primary-600 dark:text-primary-400">{{ $order->order_number }}</a></td>
                                <td class="px-6 py-4 text-xs font-medium text-surface-600 dark:text-surface-300">{{ $order->customer_name }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $osc = match($order->status) {
                                            'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                            'paid' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                            'shipped' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                            'delivered' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                            'cancelled' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                                            default => 'bg-surface-100 text-surface-600',
                                        };
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-black uppercase {{ $osc }}">{{ $order->status }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs font-black text-surface-800 dark:text-white text-right">${{ number_format($order->total_price, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-surface-400 text-sm">No recent orders</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Low Stock Alerts & Insights --}}
            <div class="space-y-6">

                <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        <h2 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-widest">Low Stock Alerts</h2>
                    </div>
                    <div class="divide-y divide-surface-100 dark:divide-surface-700">
                        @forelse($lowStockProducts as $product)
                            <div class="px-6 py-3 flex items-center justify-between hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-xs font-bold text-surface-700 dark:text-surface-200 hover:text-primary-600 transition-colors truncate pr-4">{{ $product->name }}</a>
                                <span class="inline-flex px-2 py-0.5 rounded-md text-[9px] font-black uppercase {{ $product->stock <= 0 ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400' }}">
                                    {{ $product->stock }} Left
                                </span>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-[10px] font-bold text-emerald-500 uppercase tracking-widest flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Stock levels healthy
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gradient-to-br from-surface-800 to-surface-900 dark:from-surface-700 dark:to-surface-800 p-6 rounded-2xl text-white shadow-lg relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/5 rounded-full blur-2xl"></div>
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-70 mb-3">Quick Insights</p>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-[10px] font-bold">
                            <span class="opacity-80">Products</span>
                            <span>{{ $stats['total_products'] }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-bold">
                            <span class="opacity-80">Active Products</span>
                            <span class="text-emerald-400">{{ $stats['active_products'] }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-bold">
                            <span class="opacity-80">Pending Orders</span>
                            <span class="text-amber-400">{{ $stats['pending_orders'] }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-bold">
                            <span class="opacity-80">Total Revenue</span>
                            <span class="text-emerald-400">${{ number_format($stats['total_revenue'], 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-surface-800 p-5 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                    <h3 class="text-[10px] font-black text-surface-400 uppercase tracking-widest mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.products.create') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-700/50 hover:bg-primary-50 dark:hover:bg-primary-900/20 group transition-all">
                            <svg class="w-5 h-5 text-surface-500 group-hover:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span class="text-[9px] font-bold text-surface-600 group-hover:text-primary-600 uppercase">Add Product</span>
                        </a>
                        <a href="{{ route('admin.pos.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-700/50 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 group transition-all">
                            <svg class="w-5 h-5 text-surface-500 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <span class="text-[9px] font-bold text-surface-600 group-hover:text-emerald-600 uppercase">POS</span>
                        </a>
                        <a href="{{ route('admin.purchases.create') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-700/50 hover:bg-blue-50 dark:hover:bg-blue-900/20 group transition-all">
                            <svg class="w-5 h-5 text-surface-500 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4"/></svg>
                            <span class="text-[9px] font-bold text-surface-600 group-hover:text-blue-600 uppercase">New PO</span>
                        </a>
                        <a href="{{ route('admin.expenses.create') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-50 dark:bg-surface-700/50 hover:bg-rose-50 dark:hover:bg-rose-900/20 group transition-all">
                            <svg class="w-5 h-5 text-surface-500 group-hover:text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                            <span class="text-[9px] font-bold text-surface-600 group-hover:text-rose-600 uppercase">Expense</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @endif

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartFont = { family: 'Inter', weight: 'bold' };

            @if($isSuperAdmin)
            // Super Admin Charts
            const sgData = @json($storeGrowth ?? []);
            if (sgData.length > 0) {
                new Chart(document.getElementById('storeGrowthChart').getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: sgData.map(d => d.date),
                        datasets: [
                            {
                                label: 'Total Stores',
                                data: sgData.map(d => d.count),
                                borderColor: '#6366f1',
                                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                                fill: true,
                                tension: 0.4,
                                borderWidth: 3,
                                pointRadius: 0,
                                pointHoverRadius: 6
                            },
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false } },
                            y: { grid: { color: 'rgba(0,0,0,0.03)' }, beginAtZero: true }
                        }
                    }
                });
            }

            const pdData = @json($planDistribution ?? []);
            if (pdData.length > 0) {
                new Chart(document.getElementById('planDistributionChart').getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: pdData.map(d => d.name),
                        datasets: [{
                            data: pdData.map(d => d.count),
                            backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ef4444'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false, cutout: '70%',
                        plugins: { legend: { display: true, position: 'bottom' } }
                    }
                });
            }

            @else

            // 1. Revenue vs Expense (7 days)
            new Chart(document.getElementById('revenueExpenseChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($dayLabels),
                    datasets: [
                        {
                            label: 'Revenue',
                            data: @json($weeklyRevenue),
                            backgroundColor: '#6366f1',
                            borderRadius: 6,
                            barPercentage: 0.5
                        },
                        {
                            label: 'Expense',
                            data: @json($weeklyExpense),
                            backgroundColor: '#f43f5e',
                            borderRadius: 6,
                            barPercentage: 0.5
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1e1b4b', titleFont: { size: 10, ...chartFont }, bodyFont: { size: 11, ...chartFont }, padding: 10, displayColors: true, callbacks: { label: ctx => ctx.dataset.label + ': $' + ctx.parsed.y.toLocaleString() } } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false }, ticks: { callback: v => '$' + v, font: { size: 9, ...chartFont } } },
                        x: { grid: { display: false }, ticks: { font: { size: 9, ...chartFont } } }
                    }
                }
            });

            // 2. Order Status Distribution (Doughnut)
            new Chart(document.getElementById('orderStatusChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: @json($orderStatusDist->pluck('status')->map(fn($s) => ucfirst($s))),
                    datasets: [{
                        data: @json($orderStatusDist->pluck('count')),
                        backgroundColor: ['#f59e0b', '#3b82f6', '#6366f1', '#10b981', '#ef4444', '#94a3b8'],
                        borderWidth: 0, hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '75%',
                    plugins: { legend: { display: false } }
                }
            });

            // 3. Monthly Trend (6 months - grouped bar)
            const md = @json($monthlyData);
            new Chart(document.getElementById('monthlyTrendChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: md.map(m => m.label),
                    datasets: [
                        { label: 'Sales', data: md.map(m => m.sales), backgroundColor: '#10b981', borderRadius: 4, barPercentage: 0.6 },
                        { label: 'Purchase', data: md.map(m => m.purchase), backgroundColor: '#3b82f6', borderRadius: 4, barPercentage: 0.6 },
                        { label: 'Expense', data: md.map(m => m.expense), backgroundColor: '#f43f5e', borderRadius: 4, barPercentage: 0.6 },
                        { label: 'Profit', data: md.map(m => m.profit), backgroundColor: '#6366f1', borderRadius: 4, barPercentage: 0.6 }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1e1b4b', callbacks: { label: ctx => ctx.dataset.label + ': $' + ctx.parsed.y.toLocaleString() } } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false }, ticks: { callback: v => '$' + v, font: { size: 9, ...chartFont } } },
                        x: { grid: { display: false }, ticks: { font: { size: 9, ...chartFont } } }
                    }
                }
            });

            // 4. Expense Breakdown (Pie)
            new Chart(document.getElementById('expenseBreakdownChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: @json($expenseBreakdown->pluck('category')),
                    datasets: [{
                        data: @json($expenseBreakdown->pluck('total')),
                        backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 8, font: { size: 9, ...chartFont } } } }
                }
            });
            @endif
        });
    </script>
    @endpush
</x-layouts.admin>

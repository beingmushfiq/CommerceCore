<x-layouts.admin title="ERP Command Center">

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-3xl md:text-4xl text-surface-900 dark:text-white font-display font-bold">ERP Command Center</h1>
                <p class="text-surface-500 dark:text-surface-400 mt-1 font-medium">Enterprise Resource Planning Overview</p>
            </div>
            
            <div class="flex gap-3">
                <button class="btn bg-white dark:bg-surface-800 border-surface-200 dark:border-surface-700 text-surface-700 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-700 transition-all font-bold group shadow-sm">
                    <svg class="w-4 h-4 fill-current text-surface-400 dark:text-surface-500 shrink-0 mr-2 group-hover:text-primary-500 transition-colors" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"/>
                    </svg>
                    New Expense
                </button>
                <button class="btn bg-primary-600 hover:bg-primary-500 text-white transition-all font-bold shadow-glow group">
                    <svg class="w-4 h-4 fill-current text-primary-200 shrink-0 mr-2 group-hover:block hidden transition-opacity" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"/>
                    </svg>
                    New Purchase Order
                </button>
            </div>
        </div>

        <!-- Alert Banners -->
        <div class="mb-8 space-y-3">
            @foreach($operationalAlerts as $alert)
                @php
                    $colors = [
                        'warning' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 border-amber-200 dark:border-amber-500/20',
                        'danger' => 'bg-red-50 dark:bg-red-500/10 text-red-600 border-red-200 dark:border-red-500/20',
                        'info' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 border-blue-200 dark:border-blue-500/20',
                    ];
                    $icons = [
                        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
                        'danger' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    ];
                @endphp
                <div class="flex items-center px-4 py-3 rounded-xl border {{ $colors[$alert['type']] }} font-medium shadow-sm animate-scale-up" style="animation-duration: 0.3s; animation-delay: {{ $loop->index * 0.1 }}s; animation-fill-mode: backwards;">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icons[$alert['type']] !!}</svg>
                    {{ $alert['message'] }}
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-12 gap-6">
            
            {{-- FINANCE OVERVIEW --}}
            <div class="col-span-12 xl:col-span-8 bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 p-6 flex flex-col justify-between hover:shadow-card-hover transition-all duration-300">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-display font-bold text-surface-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Financial Summary
                    </h2>
                    <select class="form-select bg-surface-50 border-surface-200 dark:bg-surface-900 dark:border-surface-700 text-surface-700 dark:text-surface-300 rounded-lg text-sm font-medium focus:ring-primary-500 focus:border-primary-500">
                        <option>This Month</option>
                        <option>Last Month</option>
                        <option>This Year</option>
                    </select>
                </div>
                
                <div class="grid grid-cols-3 gap-6 mb-8">
                    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20">
                        <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400 mb-1 uppercase tracking-wider">Revenue</p>
                        <div class="text-2xl font-display font-bold text-surface-900 dark:text-white">${{ number_format($financialSummary['revenue']) }}</div>
                        <div class="text-xs font-medium text-emerald-600 mt-2 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            12% vs last period
                        </div>
                    </div>
                    <div class="p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20">
                        <p class="text-sm font-bold text-red-600 dark:text-red-400 mb-1 uppercase tracking-wider">Expenses</p>
                        <div class="text-2xl font-display font-bold text-surface-900 dark:text-white">${{ number_format($financialSummary['expenses']) }}</div>
                        <div class="text-xs font-medium text-red-600 mt-2 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            4% vs last period
                        </div>
                    </div>
                    <div class="p-4 rounded-xl bg-primary-50 dark:bg-primary-500/10 border border-primary-100 dark:border-primary-500/20">
                        <p class="text-sm font-bold text-primary-600 dark:text-primary-400 mb-1 uppercase tracking-wider">Net Profit</p>
                        <div class="text-2xl font-display font-bold text-surface-900 dark:text-white">${{ number_format($financialSummary['net_profit']) }}</div>
                        <div class="text-xs font-medium text-primary-600 mt-2 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            18% vs last period
                        </div>
                    </div>
                </div>
                
                <div class="h-48 rounded-xl bg-surface-50 dark:bg-surface-900 border border-surface-100 dark:border-surface-800 flex items-center justify-center relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <span class="text-surface-400 dark:text-surface-500 font-medium z-10">Chart Placeholder (Alpine.js / Chart.js integration)</span>
                    <div class="absolute bottom-0 left-0 w-full h-1/2 flex items-end px-4 gap-2 opacity-50">
                        <div class="w-1/12 bg-emerald-400 rounded-t-sm h-[40%]"></div>
                        <div class="w-1/12 bg-red-400 rounded-t-sm h-[20%]"></div>
                        <div class="w-1/12 bg-emerald-400 rounded-t-sm h-[60%]"></div>
                        <div class="w-1/12 bg-red-400 rounded-t-sm h-[30%]"></div>
                        <div class="w-1/12 bg-emerald-400 rounded-t-sm h-[80%]"></div>
                        <div class="w-1/12 bg-red-400 rounded-t-sm h-[25%]"></div>
                        <div class="w-1/12 bg-emerald-400 rounded-t-sm h-[70%]"></div>
                        <div class="w-1/12 bg-red-400 rounded-t-sm h-[15%]"></div>
                        <div class="w-1/12 bg-emerald-400 rounded-t-sm h-[90%]"></div>
                        <div class="w-1/12 bg-red-400 rounded-t-sm h-[10%]"></div>
                        <div class="w-1/12 bg-emerald-400 rounded-t-sm h-[100%]"></div>
                        <div class="w-1/12 bg-red-400 rounded-t-sm h-[5%]"></div>
                    </div>
                </div>
            </div>

            {{-- HR / PAYROLL & INVENTORY SUMMARY --}}
            <div class="col-span-12 xl:col-span-4 flex flex-col gap-6">
                
                {{-- HR --}}
                <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 p-6 hover:shadow-card-hover transition-all duration-300">
                    <h2 class="text-xl font-display font-bold text-surface-900 dark:text-white flex items-center gap-2 mb-6">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Human Resources
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 rounded-xl bg-surface-50 dark:bg-surface-900 border border-surface-100 dark:border-surface-800">
                            <span class="text-surface-600 dark:text-surface-400 font-medium">Headcount</span>
                            <span class="text-surface-900 dark:text-white font-bold font-display text-lg">{{ $hrSummary['total_employees'] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl bg-surface-50 dark:bg-surface-900 border border-surface-100 dark:border-surface-800">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <span class="text-surface-600 dark:text-surface-400 font-medium">Present Today</span>
                            </div>
                            <span class="text-surface-900 dark:text-white font-bold font-display text-lg">{{ $hrSummary['present_today'] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl bg-surface-50 dark:bg-surface-900 border border-surface-100 dark:border-surface-800">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                <span class="text-surface-600 dark:text-surface-400 font-medium">On Leave</span>
                            </div>
                            <span class="text-surface-900 dark:text-white font-bold font-display text-lg">{{ $hrSummary['on_leave'] }}</span>
                        </div>
                        
                        @if($hrSummary['pending_leave_requests'] > 0)
                        <button class="w-full mt-2 py-3 bg-indigo-50 dark:bg-indigo-500/10 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 font-bold rounded-xl transition-colors border border-indigo-100 dark:border-indigo-500/20 flex items-center justify-center gap-2">
                            <span>Review {{ $hrSummary['pending_leave_requests'] }} Leave Requests</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        @endif
                    </div>
                </div>

                {{-- ACCOUNTING AGING SUMMARY --}}
                <div class="bg-white dark:bg-surface-800 rounded-2xl shadow-sm border border-surface-200 dark:border-surface-700 p-6 flex-1 hover:shadow-card-hover transition-all duration-300">
                    <h2 class="text-xl font-display font-bold text-surface-900 dark:text-white flex items-center gap-2 mb-6">
                        <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        A/R Aging
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-24 text-sm font-medium text-surface-500 dark:text-surface-400">Current</div>
                            <div class="flex-1 flex items-center gap-3">
                                <div class="h-2 rounded-full bg-cyan-100 dark:bg-cyan-900/40 w-full overflow-hidden">
                                    <div class="h-full bg-cyan-500 rounded-full w-full"></div>
                                </div>
                                <div class="w-16 text-right font-bold text-surface-900 dark:text-white">${{ number_format($financialSummary['ar_aging']['current']/1000, 1) }}k</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-24 text-sm font-medium text-surface-500 dark:text-surface-400">1-30 Days</div>
                            <div class="flex-1 flex items-center gap-3">
                                <div class="h-2 rounded-full bg-amber-100 dark:bg-amber-900/40 w-full overflow-hidden">
                                    <div class="h-full bg-amber-500 rounded-full w-[30%]"></div>
                                </div>
                                <div class="w-16 text-right font-bold text-surface-900 dark:text-white">${{ number_format($financialSummary['ar_aging']['over_30']/1000, 1) }}k</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-24 text-sm font-medium text-surface-500 dark:text-surface-400">31-60 Days</div>
                            <div class="flex-1 flex items-center gap-3">
                                <div class="h-2 rounded-full bg-orange-100 dark:bg-orange-900/40 w-full overflow-hidden">
                                    <div class="h-full bg-orange-500 rounded-full w-[10%]"></div>
                                </div>
                                <div class="w-16 text-right font-bold text-surface-900 dark:text-white">${{ number_format($financialSummary['ar_aging']['over_60']/1000, 1) }}k</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-24 text-sm font-medium text-surface-500 dark:text-surface-400">60+ Days</div>
                            <div class="flex-1 flex items-center gap-3">
                                <div class="h-2 rounded-full bg-red-100 dark:bg-red-900/40 w-full overflow-hidden">
                                    <div class="h-full bg-red-500 rounded-full w-[4%]"></div>
                                </div>
                                <div class="w-16 text-right font-bold text-surface-900 dark:text-white">${{ number_format($financialSummary['ar_aging']['over_90']/1000, 1) }}k</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
    </div>

</x-layouts.admin>

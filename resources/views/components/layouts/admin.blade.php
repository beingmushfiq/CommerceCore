<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false', spotlight: false }"
      :class="{ 'dark': darkMode }">
<head>
    @php
        $store = auth()->user()->store ?? auth()->user()->ownedStores()->first();
        $companyName = $store ? $store->name : 'CommerceCore';
        $companyLogo = $store && $store->logo ? asset('storage/' . $store->logo) : asset('images/favicon.png');
        $isSuperAdmin = auth()->user()->isSuperAdmin();
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} — {{ $companyName }}</title>
    <link rel="icon" type="image/png" href="{{ $companyLogo }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900|outfit:400,500,600,700,800,900" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="font-sans antialiased bg-surface-50 dark:bg-surface-950 text-surface-800 dark:text-surface-200 transition-colors duration-300">

    {{-- Toast --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
         class="fixed top-5 right-5 z-[99] flex items-center gap-3 px-5 py-3.5 bg-emerald-500 text-white rounded-2xl shadow-xl shadow-emerald-500/25 animate-slide-in-right">
        <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>
        <span class="font-semibold text-sm">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Spotlight Search --}}
    <div x-show="spotlight" x-cloak @keydown.escape.window="spotlight = false" @keydown.meta.k.window.prevent="spotlight = !spotlight" @keydown.ctrl.k.window.prevent="spotlight = !spotlight"
         class="fixed inset-0 z-[100] flex items-start justify-center pt-[15vh]">
        <div @click="spotlight = false" class="absolute inset-0 spotlight-overlay" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>
        <div class="relative w-full max-w-xl bg-white dark:bg-surface-800 rounded-2xl shadow-2xl border border-surface-200 dark:border-surface-700 overflow-hidden animate-scale-up" @click.away="spotlight = false">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-surface-100 dark:border-surface-700">
                <svg class="w-5 h-5 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Search products, orders, customers..." class="flex-1 bg-transparent border-none outline-none text-sm font-medium text-surface-800 dark:text-white placeholder-surface-400 focus:ring-0 p-0" autofocus>
                <kbd class="px-2 py-0.5 rounded-md bg-surface-100 dark:bg-surface-700 text-[10px] font-bold text-surface-500 border border-surface-200 dark:border-surface-600">ESC</kbd>
            </div>
            <div class="px-5 py-6 text-center text-sm text-surface-400">Type to search across your store...</div>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden">

        {{-- ====== SIDEBAR ====== --}}
        <aside :class="sidebarOpen ? 'w-[260px]' : 'w-[72px]'"
               class="hidden lg:flex flex-col bg-white dark:bg-surface-900 border-r border-surface-200/80 dark:border-surface-800 transition-all duration-300 ease-in-out relative z-30"
               x-data="{ activeGroup: '{{ request()->is('admin/products*','admin/categories*','admin/orders*','admin/customers*','admin/coupons*') ? 'commerce' : (request()->is('admin/pos*') ? 'pos' : (request()->is('admin/accounts*','admin/transactions*','admin/assets*','admin/expenses*','admin/employees*','admin/attendance*','admin/payroll*','admin/leaves*','admin/reports*') ? 'erp' : (request()->is('admin/purchases*','admin/suppliers*','admin/inventory*','admin/branches*','admin/returns*') ? 'supply' : (request()->is('admin/couriers*','admin/shipments*') ? 'logistics' : (request()->is('admin/marketing*','admin/intelligence*','admin/subscribers*','admin/inquiries*','admin/agent*') ? 'crm' : (request()->is('admin/builder*') ? 'builder' : 'core')))))) }}' }">

            {{-- Logo --}}
            <div class="flex items-center gap-3 px-5 h-[65px] border-b border-surface-100 dark:border-surface-800 flex-shrink-0">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-700 shadow-lg shadow-primary-500/25">
                    <img src="{{ $companyLogo }}" alt="Logo" class="w-7 h-7 object-contain rounded-lg">
                </div>
                <div x-show="sidebarOpen" x-transition.opacity class="overflow-hidden">
                    <h1 class="font-display font-bold text-base gradient-text truncate leading-tight">{{ $companyName }}</h1>
                    <p class="text-[9px] font-bold text-surface-400 uppercase tracking-widest">Business OS</p>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 overflow-y-auto py-3 px-3 space-y-0.5">

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'nav-item-active font-semibold' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span x-show="sidebarOpen" x-transition.opacity>Dashboard</span>
                </a>

                @if($isSuperAdmin)
                <a href="{{ route('admin.stores.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.stores.*') ? 'nav-item-active font-semibold' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span x-show="sidebarOpen" x-transition.opacity>Stores</span>
                </a>
                @endif

                {{-- COMMERCE GROUP --}}
                <div class="pt-3 mt-2" x-show="sidebarOpen"><p class="px-3 mb-1 text-[10px] font-black text-surface-400/70 uppercase tracking-[0.15em]">Commerce</p></div>
                <button @click="activeGroup = activeGroup === 'commerce' ? '' : 'commerce'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-600 dark:text-surface-400 hover:bg-surface-50 dark:hover:bg-surface-800 transition-all duration-200" :class="activeGroup === 'commerce' ? 'bg-surface-50 dark:bg-surface-800 text-surface-900 dark:text-white font-semibold' : ''" x-show="sidebarOpen">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span class="flex-1 text-left">Commerce</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="activeGroup === 'commerce' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="nav-group-content pl-3 space-y-0.5" :class="activeGroup === 'commerce' ? 'expanded' : ''">
                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.products.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.products.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Products
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.categories.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.categories.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Categories
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.orders.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.orders.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Orders
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.customers.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.customers.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Customers
                    </a>
                    <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.coupons.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.coupons.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Coupons
                    </a>
                </div>

                {{-- POS --}}
                <a href="{{ route('admin.pos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.pos.*') ? 'nav-item-active font-semibold' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-50 dark:hover:bg-surface-800' }}" x-show="sidebarOpen">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span class="flex-1">Point of Sale</span>
                    <span class="px-1.5 py-0.5 rounded-md bg-gradient-to-r from-emerald-500 to-teal-500 text-[8px] font-black text-white uppercase tracking-wider">Live</span>
                </a>

                {{-- ERP GROUP --}}
                <div class="pt-3 mt-2" x-show="sidebarOpen"><p class="px-3 mb-1 text-[10px] font-black text-surface-400/70 uppercase tracking-[0.15em]">Enterprise</p></div>
                <button @click="activeGroup = activeGroup === 'erp' ? '' : 'erp'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-600 dark:text-surface-400 hover:bg-surface-50 dark:hover:bg-surface-800 transition-all duration-200" :class="activeGroup === 'erp' ? 'bg-surface-50 dark:bg-surface-800 text-surface-900 dark:text-white font-semibold' : ''" x-show="sidebarOpen">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span class="flex-1 text-left">ERP System</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="activeGroup === 'erp' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="nav-group-content pl-3 space-y-0.5" :class="activeGroup === 'erp' ? 'expanded' : ''">
                    <a href="{{ route('erp.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('erp.dashboard') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('erp.dashboard') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Command Center
                    </a>
                    <a href="{{ route('admin.accounts.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.accounts.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.accounts.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Chart of Accounts
                    </a>
                    <a href="{{ route('journal.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('journal.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('journal.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Journal Entries
                    </a>
                    <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.transactions.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.transactions.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Transactions
                    </a>
                    <a href="{{ route('admin.expenses.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.expenses.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.expenses.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Expenses
                    </a>
                    <a href="{{ route('admin.assets.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.assets.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.assets.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Assets
                    </a>
                    <a href="{{ route('admin.reports.accounting') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.reports.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.reports.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Reports
                    </a>
                    <a href="{{ route('admin.employees.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.employees.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.employees.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Employees
                    </a>
                    <a href="{{ route('admin.payroll.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.payroll.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.payroll.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Payroll
                    </a>
                    <a href="{{ route('admin.attendance.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.attendance.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.attendance.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Attendance
                    </a>
                    <a href="{{ route('admin.leaves.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.leaves.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.leaves.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Leaves
                    </a>
                </div>

                {{-- SUPPLY CHAIN GROUP --}}
                <button @click="activeGroup = activeGroup === 'supply' ? '' : 'supply'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-600 dark:text-surface-400 hover:bg-surface-50 dark:hover:bg-surface-800 transition-all duration-200" :class="activeGroup === 'supply' ? 'bg-surface-50 dark:bg-surface-800 text-surface-900 dark:text-white font-semibold' : ''" x-show="sidebarOpen">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span class="flex-1 text-left">Supply Chain</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="activeGroup === 'supply' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="nav-group-content pl-3 space-y-0.5" :class="activeGroup === 'supply' ? 'expanded' : ''">
                    <a href="{{ route('admin.purchases.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.purchases.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.purchases.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Purchase Orders
                    </a>
                    <a href="{{ route('admin.suppliers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.suppliers.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.suppliers.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Suppliers
                    </a>
                    <a href="{{ route('admin.inventory.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.inventory.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.inventory.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Inventory & Warehouses
                    </a>
                    <a href="{{ route('admin.inventory-transfers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.inventory-transfers.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.inventory-transfers.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Inventory Transfers
                    </a>
                    <a href="{{ route('admin.branches.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.branches.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.branches.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Branches
                    </a>
                    <a href="{{ route('admin.returns.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.returns.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.returns.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Returns & Refunds
                    </a>
                    <a href="{{ route('admin.couriers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.couriers.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.couriers.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Couriers
                    </a>
                    <a href="{{ route('admin.shipments.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.shipments.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.shipments.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Shipments
                    </a>
                </div>

                {{-- CRM GROUP --}}
                <div class="pt-3 mt-2" x-show="sidebarOpen"><p class="px-3 mb-1 text-[10px] font-black text-surface-400/70 uppercase tracking-[0.15em]">Growth</p></div>
                <button @click="activeGroup = activeGroup === 'crm' ? '' : 'crm'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-600 dark:text-surface-400 hover:bg-surface-50 dark:hover:bg-surface-800 transition-all duration-200" :class="activeGroup === 'crm' ? 'bg-surface-50 dark:bg-surface-800 text-surface-900 dark:text-white font-semibold' : ''" x-show="sidebarOpen">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    <span class="flex-1 text-left">CRM & Marketing</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="activeGroup === 'crm' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="nav-group-content pl-3 space-y-0.5" :class="activeGroup === 'crm' ? 'expanded' : ''">
                    <a href="{{ route('admin.intelligence.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.intelligence.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.intelligence.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Intelligence
                    </a>
                    <a href="{{ route('admin.marketing.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.marketing.*') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.marketing.*') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Campaigns
                    </a>
                    <a href="{{ route('admin.crm.subscribers') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.crm.subscribers') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.crm.subscribers') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Subscribers
                    </a>
                    <a href="{{ route('admin.crm.inquiries') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors {{ request()->routeIs('admin.crm.inquiries') ? 'text-primary-600 dark:text-primary-400 font-semibold bg-primary-50/50 dark:bg-primary-900/20' : 'text-surface-500 dark:text-surface-400 hover:text-surface-800 dark:hover:text-white hover:bg-surface-50 dark:hover:bg-surface-800' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.crm.inquiries') ? 'bg-primary-500' : 'bg-surface-300 dark:bg-surface-600' }}"></span>Inquiries
                        @php $newInq = \App\Models\ContactSubmission::where('store_id', $adminStore->id ?? 0)->where('status', 'new')->count(); @endphp
                        @if($newInq > 0)<span class="ml-auto px-1.5 py-0.5 bg-amber-500 text-white text-[8px] font-black rounded-md">{{ $newInq }}</span>@endif
                    </a>
                </div>

                {{-- BUILDER --}}
                <a href="{{ route('admin.builder.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.builder.*') ? 'nav-item-active font-semibold' : 'text-surface-600 dark:text-surface-400 hover:bg-surface-50 dark:hover:bg-surface-800' }}" x-show="sidebarOpen">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                    <span class="flex-1">Website Builder</span>
                    <span class="px-1.5 py-0.5 rounded-md bg-gradient-to-r from-violet-500 to-purple-500 text-[8px] font-black text-white uppercase tracking-wider">Pro</span>
                </a>

                {{-- SETTINGS --}}
                @if(isset($adminStore))
                <div class="pt-3 mt-2" x-show="sidebarOpen"><p class="px-3 mb-1 text-[10px] font-black text-surface-400/70 uppercase tracking-[0.15em]">Settings</p></div>
                <a href="{{ route('admin.stores.settings', $adminStore) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 text-surface-600 dark:text-surface-400 hover:bg-surface-50 dark:hover:bg-surface-800" x-show="sidebarOpen">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Store Settings</span>
                </a>
                @endif
            </nav>

            {{-- Sidebar Toggle --}}
            <div class="border-t border-surface-100 dark:border-surface-800 p-3 flex-shrink-0">
                <button @click="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebarOpen', sidebarOpen)" class="w-full flex items-center justify-center p-2 rounded-xl text-surface-400 hover:bg-surface-50 dark:hover:bg-surface-800 transition-colors">
                    <svg x-show="sidebarOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                    <svg x-show="!sidebarOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                </button>
            </div>
        </aside>

        {{-- ====== MAIN CONTENT ====== --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Top Header --}}
            <header class="bg-white/80 dark:bg-surface-900/80 backdrop-blur-xl border-b border-surface-200/60 dark:border-surface-800 px-6 h-[65px] flex items-center justify-between flex-shrink-0 z-20">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-display font-bold text-surface-900 dark:text-white leading-tight">{{ $header ?? 'Dashboard' }}</h1>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Search Trigger --}}
                    <button @click="spotlight = true" class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-xl bg-surface-50 dark:bg-surface-800 border border-surface-200/60 dark:border-surface-700 hover:border-primary-300 dark:hover:border-primary-700 transition-colors text-surface-400 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span class="text-xs font-medium">Search...</span>
                        <kbd class="px-1.5 py-0.5 rounded bg-surface-200/60 dark:bg-surface-700 text-[9px] font-bold text-surface-500 ml-4">⌘K</kbd>
                    </button>

                    {{-- Store Preview --}}
                    @if(isset($adminStore))
                    <a href="{{ route('storefront.home', $adminStore->slug) }}" target="_blank" class="hidden sm:flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20 rounded-xl hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Store
                    </a>
                    @endif

                    {{-- Notifications --}}
                    <div x-data="{ notifOpen: false }" class="relative">
                        <button @click="notifOpen = !notifOpen" class="relative p-2 rounded-xl text-surface-500 hover:bg-surface-50 dark:hover:bg-surface-800 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full"></span>
                        </button>
                        <div x-show="notifOpen" @click.away="notifOpen = false" x-transition class="absolute right-0 mt-2 w-72 bg-white dark:bg-surface-800 rounded-2xl shadow-2xl border border-surface-200 dark:border-surface-700 py-2 z-50">
                            <p class="px-4 py-2 text-xs font-bold text-surface-400 uppercase tracking-wider">Notifications</p>
                            <div class="px-4 py-6 text-center text-xs text-surface-400">No new notifications</div>
                        </div>
                    </div>

                    {{-- Dark Mode --}}
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-xl text-surface-500 hover:bg-surface-50 dark:hover:bg-surface-800 transition-colors">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>

                    {{-- User Menu --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-surface-50 dark:hover:bg-surface-800 transition-colors">
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-primary-500 to-violet-600 flex items-center justify-center text-white text-sm font-bold shadow-lg shadow-primary-500/20">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden sm:block text-sm font-semibold text-surface-700 dark:text-surface-300">{{ auth()->user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-52 bg-white dark:bg-surface-800 rounded-2xl shadow-2xl border border-surface-200 dark:border-surface-700 py-2 z-50">
                            <div class="px-4 py-2 border-b border-surface-100 dark:border-surface-700">
                                <p class="text-sm font-bold text-surface-800 dark:text-white">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-surface-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-surface-600 dark:text-surface-300 hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>

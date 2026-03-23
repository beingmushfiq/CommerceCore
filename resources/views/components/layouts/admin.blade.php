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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-mesh text-slate-800 dark:text-slate-100 transition-colors duration-500 overflow-hidden">
    <div class="mesh-glow"></div>

    {{-- Toast --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
         class="fixed top-5 right-5 z-[99] flex items-center gap-3 px-5 py-3.5 bg-indigo-600 text-white rounded-2xl shadow-xl shadow-indigo-600/25 animate-slide-in-right">
        <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>
        <span class="font-semibold text-sm">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Spotlight Search --}}
    <div x-show="spotlight" x-cloak @keydown.escape.window="spotlight = false" @keydown.meta.k.window.prevent="spotlight = !spotlight" @keydown.ctrl.k.window.prevent="spotlight = !spotlight"
         class="fixed inset-0 z-[100] flex items-start justify-center pt-[15vh]">
        <div @click="spotlight = false" class="absolute inset-0 spotlight-overlay"></div>
        <div class="relative w-full max-w-xl glass-card rounded-2xl overflow-hidden animate-scale-up" @click.away="spotlight = false">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Search anything..." class="flex-1 bg-transparent border-none outline-none text-base font-medium text-slate-800 dark:text-white placeholder-slate-400 focus:ring-0 p-0" autofocus>
                <kbd class="px-2 py-0.5 rounded-md bg-white/10 text-[10px] font-bold text-slate-400 border border-white/5">ESC</kbd>
            </div>
            <div class="px-6 py-8 text-center text-sm text-slate-500">
                <span class="block mb-2 text-indigo-500/80 font-bold uppercase tracking-widest text-[10px]">Quick Access</span>
                Searching across your CommerceCore ecosystem...
            </div>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden">

        {{-- ====== SIDEBAR ====== --}}
        <aside :class="sidebarOpen ? 'w-[280px]' : 'w-[88px]'"
               class="hidden lg:flex flex-col glass-sidebar transition-all duration-500 cubic-bezier(0.16, 1, 0.3, 1) relative z-50 border-r border-white/10"
               x-data="{ activeGroup: '{{ request()->is('admin/products*','admin/categories*','admin/orders*','admin/customers*','admin/coupons*') ? 'commerce' : (request()->is('admin/pos*') ? 'pos' : (request()->is('admin/accounts*','admin/transactions*','admin/assets*','admin/expenses*','admin/employees*','admin/attendance*','admin/payroll*','admin/leaves*','admin/reports*') ? 'erp' : (request()->is('admin/purchases*','admin/suppliers*','admin/inventory*','admin/branches*','admin/returns*') ? 'supply' : (request()->is('admin/logistics*','admin/shipments*') ? 'logistics' : (request()->is('admin/marketing*','admin/intelligence*','admin/subscribers*','admin/inquiries*','admin/agent*') ? 'crm' : (request()->is('admin/builder*') ? 'builder' : 'core')))))) }}' }">

            {{-- Logo Area --}}
            <div class="flex items-center gap-4 px-6 h-[90px] flex-shrink-0">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center flex-shrink-0 bg-indigo-600 shadow-lg shadow-indigo-600/30 group">
                    <img src="{{ $companyLogo }}" alt="Logo" class="w-7 h-7 object-contain group-hover:scale-110 transition-transform">
                </div>
                <div x-show="sidebarOpen" x-transition.opacity class="overflow-hidden">
                    <h1 class="font-display font-black text-xl text-slate-900 dark:text-white truncate leading-none tracking-tight">{{ $companyName }}</h1>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span>
                        <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em]">Quantum Core</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 space-y-8 custom-scrollbar pt-6">
                
                {{-- SECTION: INTELLIGENCE --}}
                <div class="space-y-2">
                    <p x-show="sidebarOpen" class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] opacity-40 mb-3">Intelligence</p>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-600/30' : 'text-slate-500 hover:bg-white/10 dark:hover:bg-indigo-500/10 hover:text-indigo-600' }}">
                        <div class="w-6 h-6 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                        </div>
                        <span x-show="sidebarOpen" class="text-sm font-bold tracking-tight">System Hub</span>
                        <div x-show="request()->routeIs('admin.dashboard')" class="absolute right-0 w-1.5 h-8 bg-white rounded-l-full shadow-[0_0_15px_white]"></div>
                    </a>
                </div>

                {{-- SECTION: CORE ENGINE --}}
                <div class="space-y-2">
                    <p x-show="sidebarOpen" class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] opacity-40 mb-3">Core Engine</p>
                    
                    {{-- COMMERCE --}}
                    <div x-data="{ open: activeGroup === 'commerce' }">
                        <button @click="open = !open; activeGroup = open ? 'commerce' : ''" 
                                class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 text-slate-500 hover:bg-white/10 dark:hover:bg-indigo-500/10 hover:text-indigo-600"
                                :class="activeGroup === 'commerce' ? 'text-indigo-600 bg-indigo-500/5 shadow-sm' : ''"
                                :class="{ 'text-indigo-600': open }">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            </div>
                            <span x-show="sidebarOpen" class="flex-1 text-left text-sm font-bold tracking-tight">Marketplace</span>
                            <svg x-show="sidebarOpen" class="w-4 h-4 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                        <div x-show="open && sidebarOpen" x-collapse class="mt-2 ml-7 pl-4 border-l border-indigo-500/20 space-y-1">
                            @foreach([['Products', 'admin.products.index'], ['Inventory', 'admin.inventory.index'], ['Direct POS', 'admin.pos.index']] as $item)
                            <a href="{{ route($item[1]) }}" class="block py-2 text-[13px] font-bold {{ request()->routeIs($item[1] . '*') ? 'text-indigo-600' : 'text-slate-400 hover:text-indigo-500' }}">
                                {{ $item[0] }}
                            </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- ENTERPRISE --}}
                    <div x-data="{ open: activeGroup === 'erp' }">
                        <button @click="open = !open; activeGroup = open ? 'erp' : ''" 
                                class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 text-slate-500 hover:bg-white/10 hover:text-indigo-600"
                                :class="activeGroup === 'erp' ? 'text-indigo-600 bg-indigo-500/5 shadow-sm' : ''">
                            <div class="w-6 h-6 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6.75h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75"/></svg>
                            </div>
                            <span x-show="sidebarOpen" class="flex-1 text-left text-sm font-bold tracking-tight">ERP Nexus</span>
                            <svg x-show="sidebarOpen" class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                        <div x-show="open && sidebarOpen" x-collapse class="mt-2 ml-7 pl-4 border-l border-indigo-500/20 space-y-1">
                            @foreach([['Accounting', 'admin.accounts.index'], ['HRM System', 'admin.employees.index'], ['Supply Chain', 'admin.purchases.index']] as $item)
                            <a href="{{ route($item[1]) }}" class="block py-2 text-[13px] font-bold {{ request()->routeIs($item[1] . '*') ? 'text-indigo-600' : 'text-slate-400 hover:text-indigo-500' }}">
                                {{ $item[0] }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- SECTION: GROWTH --}}
                <div class="space-y-2">
                    {{-- BILLING --}}
                    <a href="{{ route('admin.billing.index') }}" 
                       class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.billing.*') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-600/30' : 'text-slate-500 hover:bg-white/10 dark:hover:bg-indigo-500/10 hover:text-indigo-600' }}">
                        <div class="w-6 h-6 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <span x-show="sidebarOpen" class="text-sm font-bold tracking-tight">Billing & SaaS</span>
                        <div x-show="request()->routeIs('admin.billing.*')" class="absolute right-0 w-1.5 h-8 bg-white rounded-l-full shadow-[0_0_15px_white]"></div>
                    </a>

                    <p x-show="sidebarOpen" class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] opacity-40 mb-3">Growth</p>
                    
                    {{-- BUILDER --}}
                    <a href="{{ route('admin.builder.index') }}" 
                       class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.builder.*') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-600/30' : 'text-slate-500 hover:bg-white/10 hover:text-indigo-600' }}">
                        <div class="w-6 h-6 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9s2.015-9 4.5-9m0 18c-5.035 0-9-4.03-9-9s3.965-9 9-9 9 4.03 9 9-3.965 9-9 9z"/></svg>
                        </div>
                        <span x-show="sidebarOpen" class="text-sm font-bold tracking-tight">Site Architect</span>
                        <div x-show="request()->routeIs('admin.builder.*')" class="absolute right-0 w-1.5 h-8 bg-white rounded-l-full shadow-[0_0_15px_white]"></div>
                    </a>

                    {{-- CRM --}}
                    <a href="{{ route('admin.crm.subscribers') }}" 
                       class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.crm.*') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-600/30' : 'text-slate-500 hover:bg-white/10 hover:text-indigo-600' }}">
                        <div class="w-6 h-6 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a5.97 5.97 0 00-.94 3.197m.94-3.197a5.991 5.991 0 015.058-2.771L12 12.75l.001.001a5.991 5.991 0 015.058 2.771L18 15.526zM15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                        </div>
                        <span x-show="sidebarOpen" class="text-sm font-bold tracking-tight">Leads & CRM</span>
                    </a>
                </div>
            </nav>

            {{-- Sidebar Footer --}}
            <div class="p-6 border-t border-white/5 space-y-4 flex-shrink-0">
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                        class="w-full flex items-center justify-center p-3 rounded-2xl bg-slate-100 dark:bg-slate-800/80 text-slate-500 hover:text-indigo-600 transition-all border border-transparent hover:border-indigo-500/30 group">
                    <svg x-show="!darkMode" class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="darkMode" x-cloak class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span x-show="sidebarOpen" class="ml-3 text-xs font-bold tracking-widest uppercase text-nowrap">Theme</span>
                </button>
                <div class="flex items-center gap-3" x-show="sidebarOpen">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 border border-emerald-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 7.72V12m0 0v4.28m0-4.28h3m-3 0H9"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Security</p>
                        <p class="text-[11px] font-bold text-slate-800 dark:text-slate-100 truncate mt-0.5">Isolated</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ====== MAIN CONTENT ====== --}}
        <div class="flex-1 flex flex-col overflow-hidden relative">
            
            {{-- Floating Mesh Decoration --}}
            <div class="absolute -top-[10%] -right-[10%] w-[40%] h-[40%] bg-indigo-500/5 blur-[120px] rounded-full pointer-events-none z-0"></div>
            <div class="absolute -bottom-[10%] -left-[10%] w-[30%] h-[30%] bg-blue-500/5 blur-[100px] rounded-full pointer-events-none z-0"></div>

            {{-- Header --}}
            <header class="glass sticky top-0 px-8 h-[90px] flex items-center justify-between flex-shrink-0 z-40 border-b border-white/10">
                <div class="flex items-center gap-6">
                    <button @click="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebarOpen', sidebarOpen)" class="p-2.5 rounded-2xl text-slate-500 hover:bg-white/10 transition-all hover:scale-110 active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h1 class="text-2xl font-display font-black text-slate-900 dark:text-white leading-tight tracking-tight">{{ $header ?? 'Hub Control' }}</h1>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Unified Intelligence Interface</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Spotlight Trigger --}}
                    <button @click="spotlight = true" class="hidden md:flex items-center gap-4 px-5 py-3 glass-card rounded-2xl text-slate-400 hover:text-indigo-400 transition-all border-none group bg-white/30 dark:bg-slate-800/30 shadow-sm">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span class="text-xs font-bold uppercase tracking-widest leading-none">Command Center</span>
                        <div class="px-2 py-1 rounded-lg bg-indigo-600/10 text-[9px] font-black ml-4 text-indigo-600 border border-indigo-600/20">⌘K</div>
                    </button>

                    <div class="h-8 w-[1px] bg-slate-200 dark:bg-white/5 mx-2"></div>

                    {{-- User Profile --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-3 p-1 rounded-2xl hover:bg-slate-100 dark:hover:bg-white/5 transition-all">
                            <div class="w-10 h-10 rounded-xl gradient-bg bg-indigo-600 flex items-center justify-center text-white text-base font-black shadow-lg shadow-indigo-500/25">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden md:block text-left mr-2">
                                <p class="text-xs font-black text-slate-800 dark:text-white leading-none uppercase tracking-widest">{{ auth()->user()->name }}</p>
                                <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase">{{ auth()->user()->roles->first()->name ?? 'Manager' }}</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 mr-2 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="absolute right-0 mt-3 w-64 glass-card rounded-3xl py-3 z-50 overflow-hidden">
                            <div class="px-6 py-4 border-b border-white/5 bg-white/5">
                                <p class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-1">Signed in as</p>
                                <p class="text-sm font-bold text-slate-800 dark:text-white truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="p-2 space-y-1">
                                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[13px] font-bold text-slate-500 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-indigo-400 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Edit Profile
                                </a>
                                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[13px] font-bold text-slate-500 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-indigo-400 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Preferences
                                </a>
                                <div class="h-[1px] bg-slate-100 dark:bg-white/5 my-2"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-[13px] font-bold text-rose-500 hover:bg-rose-500/10 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        End Session
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Body --}}
            <main class="flex-1 overflow-y-auto relative z-10 custom-scrollbar p-8 pt-4">
                <div class="max-w-[1600px] mx-auto animate-fade-in-up">
                    {{ $slot }}
                </div>
            </main>
        </div>

        {{-- AI Assistant Floating Trigger --}}
        <div x-data="{ open: false }" class="fixed bottom-8 right-8 z-[100]">
            <button @click="open = !open" 
                    class="w-16 h-16 rounded-3xl bg-indigo-600 text-white flex items-center justify-center shadow-3xl shadow-indigo-600/40 hover:scale-110 active:scale-95 transition-all group relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <svg x-show="!open" class="w-8 h-8 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                <svg x-show="open" x-cloak class="w-8 h-8 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-12 scale-90" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 @click.away="open = false"
                 class="absolute bottom-20 right-0 w-[420px] h-[600px] glass-card rounded-[32px] shadow-3xl border border-white/20 overflow-hidden flex flex-col z-[101]">
                <div class="p-8 bg-indigo-600 text-white relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 blur-3xl rounded-full"></div>
                    <h3 class="font-display font-black text-2xl leading-none relative z-10">Quantum Intel</h3>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] mt-3 opacity-70 relative z-10">Neural Hub Assistant</p>
                </div>
                <div class="flex-1 p-6 space-y-6 overflow-y-auto custom-scrollbar bg-slate-50/50 dark:bg-slate-900/50">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center flex-shrink-0 text-white shadow-lg shadow-indigo-600/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl rounded-tl-none border border-slate-100 dark:border-white/5 shadow-sm max-w-[85%]">
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200 leading-relaxed">System operational. I am connected to the CommerceCore neural net. How may I assist your operations today?</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-slate-100 dark:border-white/10 bg-white dark:bg-slate-900">
                    <div class="relative">
                        <input type="text" placeholder="Command the system..." class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all pr-14 placeholder-slate-400">
                        <button class="absolute right-3 top-1/2 -translate-y-1/2 text-indigo-500 hover:scale-125 transition-all p-2 bg-indigo-500/10 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
    <div id="portal-root"></div>
</body>
</html>

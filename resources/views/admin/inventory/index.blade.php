<x-layouts.admin>
    <x-slot:header>Inventory & Warehouses</x-slot:header>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        {{-- Warehouse Zones --}}
        <div class="xl:col-span-1 space-y-6">
            <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-wider mb-4 flex justify-between items-center">
                    Warehouse Zones
                </h3>
                
                <div class="space-y-3 mb-6">
                    @forelse($zones as $zone)
                        <div class="flex items-center justify-between p-3 rounded-xl border border-surface-100 dark:border-surface-700 bg-surface-50 dark:bg-surface-900/50 hover:border-primary-300 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-surface-900 dark:text-white">{{ $zone->name }}</h4>
                                    <p class="text-xs text-surface-500 uppercase tracking-wide">{{ $zone->type }} &bull; {{ $zone->inventories_count }} items</p>
                                </div>
                            </div>
                            <button class="text-surface-400 hover:text-primary-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-6 text-surface-500 text-sm">No zones configured.</div>
                    @endforelse
                </div>

                <form action="{{ route('admin.inventory.zone.store') }}" method="POST" class="space-y-3 border-t border-surface-200 dark:border-surface-700 pt-4">
                    @csrf
                    <h4 class="text-xs font-bold text-surface-500 uppercase">Add New Zone</h4>
                    <input type="text" name="name" required placeholder="Zone Name (e.g. A1-Rack)" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white transition-colors focus:ring-primary-500 focus:border-primary-500">
                    <div class="grid grid-cols-2 gap-3">
                        <select name="type" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white transition-colors focus:ring-primary-500 focus:border-primary-500">
                            <option value="storage">Storage</option>
                            <option value="receiving">Receiving</option>
                            <option value="picking">Picking</option>
                        </select>
                        <input type="number" name="capacity" placeholder="Cap. (Opt)" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white transition-colors focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <button type="submit" class="w-full py-2 bg-surface-100 hover:bg-surface-200 dark:bg-surface-700 dark:hover:bg-surface-600 text-surface-800 dark:text-white text-sm font-bold rounded-xl transition-all">
                        + Create Zone
                    </button>
                </form>
            </div>
        </div>

        {{-- Reorder Points --}}
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6 shadow-sm overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/5 dark:bg-amber-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>

                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-surface-900 dark:text-white flex items-center gap-2">
                            <span class="relative flex h-3 w-3">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                            </span>
                            Reorder Points Action Center
                        </h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400 mt-1">Products currently below their designated minimum stock thresholds.</p>
                    </div>
                    
                    <form action="{{ route('admin.inventory.reorder.auto') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary bg-amber-500 hover:bg-amber-600 shadow-amber-500/20 text-white border-0 flex items-center gap-2" {{ $lowStockProducts->count() == 0 ? 'disabled' : '' }}>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Auto-Generate POs
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-surface-50 dark:bg-surface-900/50 text-xs font-semibold text-surface-500 uppercase tracking-wider border-b border-surface-200 dark:border-surface-700">
                            <tr>
                                <th class="px-6 py-4">Product</th>
                                <th class="px-6 py-4">SKU</th>
                                <th class="px-6 py-4 text-center">Alert Qty</th>
                                <th class="px-6 py-4 text-center">Current Stock</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-100 dark:divide-surface-700/50">
                            @forelse($lowStockProducts as $product)
                                <tr class="hover:bg-surface-50 dark:hover:bg-surface-800/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($product->image)
                                                <img src="{{ Storage::url($product->image) }}" class="w-8 h-8 rounded shrink-0 object-cover border border-surface-200">
                                            @else
                                                <div class="w-8 h-8 rounded shrink-0 bg-surface-100 dark:bg-surface-800 flex items-center justify-center text-surface-400 border border-surface-200 dark:border-surface-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                            <span class="font-medium text-surface-900 dark:text-white">{{ $product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-surface-500">
                                        {{ $product->sku ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium text-surface-600 dark:text-surface-400">
                                        {{ $product->alert_quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $product->stock == 0 ? 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($product->stock == 0)
                                            <span class="text-rose-500 font-bold text-[10px] uppercase">Out of Stock</span>
                                        @else
                                            <span class="text-amber-500 font-bold text-[10px] uppercase">Low Stock</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.purchases.create', ['product_id' => $product->id]) }}" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 font-medium text-xs uppercase tracking-wider transition-colors">Draft PO</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-500 mb-4">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <h3 class="text-base font-bold text-surface-900 dark:text-white">Inventory Healthy</h3>
                                        <p class="text-sm text-surface-500 mt-1">All products are currently above their reorder thresholds.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Inventory Transfers & Stock Movements mini card --}}
            <div class="grid grid-cols-2 gap-6">
                 <a href="{{ route('admin.inventory.transfers.index') }}" class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6 shadow-sm hover:border-primary-500 hover:shadow-primary-500/10 transition-all group">
                     <div class="w-10 h-10 rounded-xl bg-surface-100 dark:bg-surface-700 text-surface-600 dark:text-surface-300 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                     </div>
                     <h4 class="font-bold text-surface-900 dark:text-white">Inventory Transfers</h4>
                     <p class="text-xs text-surface-500 mt-1">Manage stock movement between branches and zones.</p>
                 </a>
                 <div class="bg-gradient-to-br from-primary-600 to-indigo-700 rounded-2xl p-6 shadow-sm relative overflow-hidden group text-white">
                     <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMiIgZmlsbD0id2hpdGUiIGZpbGwtb3BhY2l0eT0iMC4xNSIvPjwvc3ZnPg==')] opacity-50 block"></div>
                     <div class="relative z-10 w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                     </div>
                     <h4 class="font-bold">Stock Valuation Report</h4>
                     <p class="text-xs text-primary-100 mt-1">View comprehensive inventory value by zone.</p>
                 </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

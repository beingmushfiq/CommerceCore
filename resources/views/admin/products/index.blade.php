<x-layouts.admin>
    <x-slot:header>Products</x-slot:header>

    <div class="space-y-6">
        {{-- Top Bar --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-display font-bold text-surface-800 dark:text-white">Products</h2>
                <p class="text-sm text-surface-500 mt-1">Manage your product catalog</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/25 transition-all hover:shadow-primary-500/40">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Product
            </a>
        </div>

        {{-- Product Analytics --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Stock Health (Doughnut) --}}
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <h3 class="text-[10px] font-black text-surface-400 uppercase tracking-widest mb-4">Stock Health Architecture</h3>
                <div class="h-40 flex items-center justify-center relative">
                    <canvas id="stockHealthChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-lg font-bold text-surface-800 dark:text-white">{{ array_sum($stockStats) }}</span>
                        <span class="text-[7px] font-black text-surface-400 uppercase">Total Items</span>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-3 gap-2">
                    <div class="text-center">
                        <p class="text-[9px] font-bold text-emerald-500 uppercase">Healthy</p>
                        <p class="text-sm font-bold text-surface-800 dark:text-white">{{ $stockStats['in_stock'] }}</p>
                    </div>
                    <div class="text-center border-x border-surface-100 dark:border-surface-700 px-2">
                        <p class="text-[9px] font-bold text-amber-500 uppercase">Warning</p>
                        <p class="text-sm font-bold text-surface-800 dark:text-white">{{ $stockStats['low_stock'] }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[9px] font-bold text-rose-500 uppercase">Critical</p>
                        <p class="text-sm font-bold text-surface-800 dark:text-white">{{ $stockStats['out_of_stock'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Top Performing Products (Bar) --}}
            <div class="lg:col-span-2 bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm relative overflow-hidden">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-[10px] font-black text-surface-400 uppercase tracking-widest">Velocity Assets (Top 5)</h3>
                    <span class="text-[9px] font-bold text-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 px-2 py-1 rounded">Ranked by Sales</span>
                </div>
                <div class="h-44">
                    <canvas id="productVelocityChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-4">
            <form method="GET" class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="flex-1 min-w-[200px] px-4 py-2 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white placeholder-surface-400">
                <select name="status" class="px-4 py-2 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
                <select name="category_id" class="px-4 py-2 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-5 py-2 bg-surface-100 dark:bg-surface-700 hover:bg-surface-200 dark:hover:bg-surface-600 text-sm font-medium rounded-xl transition-colors dark:text-white">Filter</button>
            </form>
        </div>

        {{-- Products Table --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-surface-50 dark:bg-surface-700/50">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Product</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Category</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Price</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Stock</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Status</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                        @forelse($products as $product)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-surface-100 dark:bg-surface-700 overflow-hidden flex-shrink-0">
                                        @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-surface-800 dark:text-white">{{ $product->name }}</p>
                                        @if($product->sku)<p class="text-xs text-surface-400">SKU: {{ $product->sku }}</p>@endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-surface-600 dark:text-surface-300">{{ $product->category?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm font-semibold text-surface-800 dark:text-white">${{ number_format($product->price, 2) }}</p>
                                @if($product->hasDiscount())
                                <p class="text-xs text-red-500 line-through">${{ number_format($product->compare_price, 2) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-medium {{ $product->stock > 0 ? 'text-emerald-600' : 'text-red-500' }}">{{ $product->stock }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $product->status === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : ($product->status === 'draft' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-surface-100 text-surface-600 dark:bg-surface-700 dark:text-surface-400') }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="p-2 text-surface-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-surface-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-surface-300 dark:text-surface-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <p class="text-surface-500 dark:text-surface-400 mb-3">No products yet</p>
                                <a href="{{ route('admin.products.create') }}" class="text-primary-600 hover:underline text-sm font-medium">Add your first product →</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($products->hasPages())
            <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Stock Health Chart
            const stockCtx = document.getElementById('stockHealthChart').getContext('2d');
            new Chart(stockCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Healthy', 'Warning', 'Critical'],
                    datasets: [{
                        data: [{{ $stockStats['in_stock'] }}, {{ $stockStats['low_stock'] }}, {{ $stockStats['out_of_stock'] }}],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: { legend: { display: false } }
                }
            });

            // Product Velocity Chart
            const velocityCtx = document.getElementById('productVelocityChart').getContext('2d');
            new Chart(velocityCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topProducts->pluck('name')->map(fn($n) => strlen($n) > 15 ? substr($n, 0, 12).'...' : $n)) !!},
                    datasets: [{
                        label: 'Orders',
                        data: {!! json_encode($topProducts->pluck('order_items_count')) !!},
                        backgroundColor: '#6366f1',
                        borderRadius: 8,
                        barThickness: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                            ticks: { font: { family: 'Inter', size: 9, weight: 'bold' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter', size: 9, weight: 'bold' } }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-layouts.admin>

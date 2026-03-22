<x-layouts.admin>
    <x-slot:header>Purchase Orders</x-slot:header>

    <div class="space-y-6">
        {{-- Top Bar --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-display font-bold text-surface-800 dark:text-white">Purchase Orders</h2>
                <p class="text-sm text-surface-500 mt-1">Manage supplier purchases and restock inventory</p>
            </div>
            <a href="{{ route('admin.purchases.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/25 transition-all hover:shadow-primary-500/40">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Purchase Order
            </a>
        </div>

        {{-- Analytics --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <p class="text-[10px] font-black text-surface-400 uppercase tracking-widest mb-1">Total Procurement</p>
                <h4 class="text-2xl font-bold text-surface-800 dark:text-white">${{ number_format($totalSpend, 2) }}</h4>
            </div>
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1">Pending Orders</p>
                <h4 class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $pendingCount }}</h4>
            </div>
            <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Received</p>
                <h4 class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $receivedCount }}</h4>
            </div>
        </div>

        {{-- Procurement Trend Chart --}}
        <div class="bg-white dark:bg-surface-800 p-6 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-[10px] font-black text-surface-400 uppercase tracking-widest">Procurement Spend (14D)</h3>
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary-500 animate-pulse"></span>
                    <span class="text-[10px] font-bold text-surface-700 dark:text-surface-300">Live</span>
                </div>
            </div>
            <div class="h-48">
                <canvas id="procurementChart"></canvas>
            </div>
        </div>

        {{-- Filters --}}
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.purchases.index') }}" class="px-4 py-2 text-sm font-medium rounded-xl {{ !request('status') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'bg-white dark:bg-surface-800 text-surface-600 dark:text-surface-400 border border-surface-200 dark:border-surface-700' }} transition-colors">All</a>
            @foreach(['pending', 'ordered', 'received', 'cancelled'] as $status)
            <a href="{{ route('admin.purchases.index', ['status' => $status]) }}" class="px-4 py-2 text-sm font-medium rounded-xl {{ request('status') === $status ? 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'bg-white dark:bg-surface-800 text-surface-600 dark:text-surface-400 border border-surface-200 dark:border-surface-700' }} transition-colors">{{ ucfirst($status) }}</a>
            @endforeach
        </div>

        {{-- Table --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-surface-50 dark:bg-surface-700/50">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">PO Number</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Supplier</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Items</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Status</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Payment</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Total</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Date</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-surface-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                        @forelse($purchases as $po)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.purchases.show', $po) }}" class="text-sm font-semibold text-primary-600 dark:text-primary-400 hover:underline">{{ $po->purchase_number }}</a>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-surface-800 dark:text-white">{{ $po->supplier_name }}</p>
                                @if($po->supplier_email)<p class="text-xs text-surface-400">{{ $po->supplier_email }}</p>@endif
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-medium text-surface-600 dark:text-surface-300">{{ $po->items_count }}</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $sc = match($po->status) {
                                        'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                        'ordered' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        'received' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'cancelled' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                                        default => 'bg-surface-100 text-surface-600',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $sc }}">{{ ucfirst($po->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $pc = match($po->payment_status) {
                                        'paid' => 'bg-emerald-100 text-emerald-700',
                                        'partial' => 'bg-amber-100 text-amber-700',
                                        default => 'bg-surface-100 text-surface-600',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $pc }}">{{ ucfirst($po->payment_status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-semibold text-surface-800 dark:text-white">${{ number_format($po->total_amount, 2) }}</td>
                            <td class="px-6 py-4 text-right text-sm text-surface-500">{{ $po->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.purchases.show', $po) }}" class="p-2 text-surface-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    @if($po->status === 'pending' || $po->status === 'ordered')
                                    <form method="POST" action="{{ route('admin.purchases.receive', $po) }}">
                                        @csrf
                                        <button type="submit" class="p-2 text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Receive Stock">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-surface-300 dark:text-surface-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                                <p class="text-surface-500 dark:text-surface-400 mb-3">No purchase orders yet</p>
                                <a href="{{ route('admin.purchases.create') }}" class="text-primary-600 hover:underline text-sm font-medium">Create your first PO →</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($purchases->hasPages())
            <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">{{ $purchases->links() }}</div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('procurementChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($monthlySpend->pluck('date')) !!},
                    datasets: [{
                        label: 'Spend',
                        data: {!! json_encode($monthlySpend->pluck('total')) !!},
                        backgroundColor: '#6366f1',
                        borderRadius: 6,
                        barThickness: 18
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
                            ticks: { callback: v => '$' + v, font: { family: 'Inter', size: 9, weight: 'bold' } }
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

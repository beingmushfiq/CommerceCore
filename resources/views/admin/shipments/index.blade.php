<x-layouts.admin>
    <x-slot:header>Shipment Tracking</x-slot:header>

    <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-50 dark:bg-surface-900/50 border-b border-surface-200 dark:border-surface-700">
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase tracking-wider">Order #</th>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase tracking-wider">Courier</th>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase tracking-wider">Tracking</th>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase tracking-wider">Cost</th>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                    @forelse($shipments as $shipment)
                    <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $shipment->order) }}" class="text-sm font-bold text-primary-600 hover:text-primary-700">
                                {{ $shipment->order->order_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-surface-600 dark:text-surface-300">
                            {{ $shipment->courier->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-mono text-surface-500">
                            {{ $shipment->tracking_number ?? 'Pending' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-surface-800 dark:text-white">
                            ${{ number_format($shipment->shipping_cost, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $shipment->status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($shipment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.shipments.status', $shipment) }}" method="POST" class="inline-flex items-center gap-2">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="text-xs border-surface-200 rounded-lg dark:bg-surface-800 dark:text-white">
                                    <option value="picked" {{ $shipment->status === 'picked' ? 'selected' : '' }}>Picked</option>
                                    <option value="in_transit" {{ $shipment->status === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                    <option value="delivered" {{ $shipment->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="returned" {{ $shipment->status === 'returned' ? 'selected' : '' }}>Returned</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-surface-500">
                            No shipments found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-surface-50 dark:bg-surface-900/50 border-t border-surface-200 dark:border-surface-700">
            {{ $shipments->links() }}
        </div>
    </div>
</x-layouts.admin>

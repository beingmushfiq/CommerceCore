<x-layouts.admin>
    <x-slot:header>Asset Management</x-slot:header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Add Asset Form --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6 h-fit shadow-sm">
            <h3 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-wider mb-4">Add Business Asset</h3>
            <form action="{{ route('admin.assets.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs text-surface-400 mb-1 font-bold">Asset Name</label>
                    <input type="text" name="name" required placeholder="Laptop, Warehouse Rack, etc." class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold">Purchase Price</label>
                        <input type="number" step="0.01" name="purchase_price" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold">Purchase Date</label>
                        <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold">Depreciation %</label>
                        <input type="number" step="0.1" name="depreciation_percentage" value="0.0" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold">Status</label>
                        <select name="status" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                            <option value="in_use">In Use</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="sold">Sold</option>
                            <option value="disposed">Disposed</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-primary-500/20">
                    Register Asset
                </button>
            </form>
        </div>

        {{-- Assets List --}}
        <div class="md:col-span-2 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 flex justify-between items-center">
                <h3 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-wider">Business Assets</h3>
                <span class="text-xs font-bold text-surface-500">Total Value: ${{ number_format($assets->sum('current_value'), 2) }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-50 dark:bg-surface-900/30 border-b border-surface-200 dark:border-surface-700">
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase">Asset</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-center">Purchased</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-right">Value (Current)</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                        @foreach($assets as $asset)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-surface-800 dark:text-white">{{ $asset->name }}</p>
                                <p class="text-[10px] text-surface-400">Depreciation: {{ $asset->depreciation_percentage }}%</p>
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-surface-500">
                                {{ $asset->purchase_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm font-bold text-primary-600">${{ number_format($asset->current_value, 2) }}</p>
                                <p class="text-[10px] text-surface-400">Orig: ${{ number_format($asset->purchase_price, 2) }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase 
                                    {{ $asset->status === 'in_use' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ str_replace('_', ' ', $asset->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">
                {{ $assets->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>

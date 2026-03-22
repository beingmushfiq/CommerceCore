<x-layouts.admin>
    <x-slot:header>Courier Management</x-slot:header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Add Courier Form --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6 h-fit">
            <h3 class="text-sm font-semibold text-surface-500 uppercase tracking-wider mb-4">Add New Courier</h3>
            <form action="{{ route('admin.couriers.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm text-surface-600 dark:text-surface-400 mb-1">Company Name</label>
                    <input type="text" name="name" required class="w-full border-surface-200 rounded-xl dark:bg-surface-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm text-surface-600 dark:text-surface-400 mb-1">Phone</label>
                    <input type="text" name="phone" class="w-full border-surface-200 rounded-xl dark:bg-surface-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm text-surface-600 dark:text-surface-400 mb-1">Email</label>
                    <input type="email" name="email" class="w-full border-surface-200 rounded-xl dark:bg-surface-900 dark:text-white">
                </div>
                <button type="submit" class="w-full py-2.5 bg-primary-600 text-white font-bold rounded-xl shadow-lg shadow-primary-500/20">
                    Add Courier
                </button>
            </form>
        </div>

        {{-- Courier List --}}
        <div class="md:col-span-2 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-50 dark:bg-surface-900/50 border-b border-surface-200 dark:border-surface-700">
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase">Courier</th>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase">Shipments</th>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase">Wallet Balance</th>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                    @foreach($couriers as $courier)
                    <tr>
                        <td class="px-6 py-4">
                            <p class="font-bold text-surface-800 dark:text-white">{{ $courier->name }}</p>
                            <p class="text-xs text-surface-500">{{ $courier->phone }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-surface-600 dark:text-surface-400">
                            {{ $courier->shipments_count }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold {{ $courier->balance < 0 ? 'text-red-500' : 'text-green-500' }}">
                                ${{ number_format($courier->balance, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase {{ $courier->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $courier->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>

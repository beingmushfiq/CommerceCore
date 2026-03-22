<x-layouts.admin>
    <x-slot:header>Leave Management</x-slot:header>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        {{-- Request Leave Form --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6 h-fit shadow-sm">
            <h3 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-wider mb-4">Request Time Off</h3>
            <form action="{{ route('admin.leaves.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs text-surface-400 mb-1 font-bold">Leave Type</label>
                    <select name="type" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                        <option value="Sick Leave">Sick Leave</option>
                        <option value="Casual Leave">Casual Leave</option>
                        <option value="Annual Leave">Annual Leave</option>
                        <option value="Maternity/Paternity">Maternity/Paternity</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold">Start Date</label>
                        <input type="date" name="start_date" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold">End Date</label>
                        <input type="date" name="end_date" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-surface-400 mb-1 font-bold">Reason</label>
                    <textarea name="reason" placeholder="Briefly explain..." rows="3" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white"></textarea>
                </div>
                <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-primary-500/20">
                    Apply Leave
                </button>
            </form>
        </div>

        {{-- Leave History --}}
        <div class="md:col-span-3 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 flex justify-between items-center">
                <h3 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-wider">Leave Applications</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-50 dark:bg-surface-900/30 border-b border-surface-200 dark:border-surface-700">
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase">Staff Member</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase">Leave Type</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase">Duration</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-right">Status</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                        @foreach($leaves as $leave)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-surface-800 dark:text-white">{{ $leave->user->name }}</p>
                                <p class="text-[10px] text-surface-400">{{ $leave->reason }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-surface-600 dark:text-surface-300">
                                {{ $leave->type }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-[10px] font-bold text-surface-400 leading-none">FROM {{ $leave->start_date->format('M d') }}</p>
                                <p class="text-[10px] font-bold text-surface-400 mt-1">TO {{ $leave->end_date->format('M d, Y') }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase 
                                    {{ $leave->status === 'approved' ? 'bg-green-100 text-green-700' : ($leave->status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ $leave->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($leave->status === 'pending')
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('admin.leaves.update', $leave) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="p-1.5 bg-green-50 text-green-600 border border-green-100 rounded hover:bg-green-100">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.leaves.update', $leave) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="p-1.5 bg-rose-50 text-rose-600 border border-rose-100 rounded hover:bg-rose-100">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-[10px] text-surface-400 uppercase font-bold italic">Closed</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">
                {{ $leaves->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>

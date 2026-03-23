<x-layouts.admin>
    <x-slot:header>Payroll & Salary Management</x-slot:header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Generate Payroll Form --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6 h-fit shadow-sm">
            <h3 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-wider mb-4">Generate Pay Slip</h3>
            <form action="{{ route('admin.payroll.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs text-surface-400 mb-1 font-bold">Employee</label>
                    <select name="employee_id" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->user->name }} ({{ $emp->designation }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-surface-400 mb-1 font-bold">Month</label>
                    <input type="month" name="month" value="{{ date('Y-m') }}" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold">Bonus</label>
                        <input type="number" step="0.01" name="bonus" value="0.00" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold">Deduction</label>
                        <input type="number" step="0.01" name="deduction" value="0.00" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                    </div>
                </div>
                <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-primary-500/20">
                    Generate Slip
                </button>
            </form>
        </div>

        {{-- Payroll History --}}
        <div class="md:col-span-2 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700">
                <h3 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-wider">Salary Slips</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-50 dark:bg-surface-900/30 border-b border-surface-200 dark:border-surface-700">
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase">Staff</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase">Month</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-right">Net Salary</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-right">Status</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                        @foreach($payrolls as $pr)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-surface-800 dark:text-white">{{ $pr->employee->user->name }}</p>
                                <p class="text-[10px] text-surface-400 uppercase tracking-tighter">{{ $pr->employee->designation }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-surface-500">
                                {{ date('F Y', strtotime($pr->month.'-01')) }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm font-black text-primary-600">${{ number_format($pr->net_salary, 2) }}</p>
                                <p class="text-[10px] text-surface-400">Basic: ${{ number_format($pr->basic_salary, 2) }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase 
                                    {{ $pr->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $pr->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($pr->status === 'pending')
                                    <form action="{{ route('admin.payroll.update', $pr) }}" method="POST" class="inline-block mr-2">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 uppercase tracking-widest border border-emerald-100 dark:border-emerald-900/50 px-2 py-1 rounded-lg transition-all hover:bg-emerald-50 dark:hover:bg-emerald-900/30">
                                            Mark Paid
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[10px] font-bold text-green-600 uppercase mr-2 inline-block">Paid {{ $pr->paid_at->format('M d') }}</span>
                                @endif
                                <a href="{{ route('admin.payroll.show', $pr) }}" class="text-[10px] font-bold text-primary-600 hover:text-primary-700 uppercase tracking-widest border border-primary-100 dark:border-primary-900/50 px-2 py-1 rounded-lg transition-all hover:bg-primary-50 dark:hover:bg-primary-900/30 inline-block">
                                    View Slip
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">
                {{ $payrolls->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>

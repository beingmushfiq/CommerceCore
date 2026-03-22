<x-layouts.admin>
    <x-slot:header>Employee Management</x-slot:header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Add Employee Form --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6 h-fit shadow-sm">
            <h3 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-wider mb-4">Onboard New Employee</h3>
            <form action="{{ route('admin.employees.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs text-surface-400 mb-1 font-bold">Account User</label>
                    <select name="user_id" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                        @foreach(\App\Models\User::all() as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-surface-400 mb-1 font-bold">Employee ID / Code</label>
                    <input type="text" name="employee_id" required value="EMP-{{ rand(1000, 9999) }}" class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold">Designation</label>
                        <input type="text" name="designation" placeholder="Sales agent" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold">Basic Salary</label>
                        <input type="number" name="basic_salary" value="0.00" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-surface-400 mb-1 font-bold">Joining Date</label>
                    <input type="date" name="joining_date" value="{{ date('Y-m-d') }}" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white">
                </div>
                <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-primary-500/20">
                    Register Staff
                </button>
            </form>
        </div>

        {{-- Employee List --}}
        <div class="md:col-span-2 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700">
                <h3 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-wider">Active Staff</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-50 dark:bg-surface-900/30 border-b border-surface-200 dark:border-surface-700">
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase">Employee Details</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-center">Join Date</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-right">Basic Salary</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                        @foreach($employees as $emp)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-[10px] font-bold text-primary-500 leading-none uppercase">{{ $emp->employee_id }}</p>
                                <p class="text-sm font-bold text-surface-800 dark:text-white mt-1">{{ $emp->user->name }}</p>
                                <p class="text-[10px] text-surface-400 font-medium uppercase tracking-tight">{{ $emp->designation }}</p>
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-surface-500">
                                {{ $emp->joining_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm font-black text-surface-800 dark:text-white">${{ number_format($emp->basic_salary, 2) }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase 
                                    {{ $emp->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-surface-100 text-surface-700' }}">
                                    {{ $emp->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>

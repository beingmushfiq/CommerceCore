<x-layouts.admin>
    <x-slot:header>Employee Profile</x-slot:header>

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.employees.index') }}" class="text-sm font-medium text-surface-500 hover:text-primary-600 transition-colors flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Back to Staff List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6 shadow-sm text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-br from-primary-500/20 to-purple-500/20 dark:from-primary-500/10 dark:to-purple-500/10"></div>
                
                <div class="relative z-10">
                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-primary-500 to-violet-600 mx-auto flex items-center justify-center text-white text-3xl font-black shadow-xl shadow-primary-500/30 mb-4 border-4 border-white dark:border-surface-800">
                        {{ strtoupper(substr($employee->user->name, 0, 1)) }}
                    </div>
                    <h2 class="text-xl font-bold text-surface-900 dark:text-white">{{ $employee->user->name }}</h2>
                    <p class="text-sm text-surface-500 dark:text-surface-400 font-medium tracking-wide mt-1">{{ $employee->designation }}</p>
                    
                    <span class="inline-flex items-center px-2.5 py-0.5 mt-3 rounded-full text-[10px] font-bold uppercase {{ $employee->status === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-surface-100 text-surface-700' }}">
                        {{ $employee->status }}
                    </span>
                </div>

                <div class="mt-8 space-y-4 text-left border-t border-surface-100 dark:border-surface-700 pt-6">
                    <div>
                        <p class="text-[10px] font-bold text-surface-400 uppercase tracking-wider mb-1">Employee ID</p>
                        <p class="text-sm font-semibold text-surface-800 dark:text-white">{{ $employee->employee_id }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-surface-400 uppercase tracking-wider mb-1">Email</p>
                        <p class="text-sm font-semibold text-surface-800 dark:text-white">{{ $employee->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-surface-400 uppercase tracking-wider mb-1">Date Joined</p>
                        <p class="text-sm font-semibold text-surface-800 dark:text-white">{{ $employee->joining_date->format('F d, Y') }} ({{ $employee->joining_date->diffForHumans() }})</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-surface-400 uppercase tracking-wider mb-1">Base Compensation</p>
                        <p class="text-lg font-black text-primary-600 dark:text-primary-400">${{ number_format($employee->basic_salary, 2) }} <span class="text-xs text-surface-400 font-medium">/mo</span></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Details & History --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Payroll History --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
                <div class="px-6 py-5 border-b border-surface-200 dark:border-surface-700 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-surface-900 dark:text-white">Compensation History</h3>
                        <p class="text-xs text-surface-500 mt-1">Recent payslips and salary records</p>
                    </div>
                    <button class="btn btn-primary bg-primary-50 text-primary-600 hover:bg-primary-100 dark:bg-primary-500/10 dark:text-primary-400 dark:hover:bg-primary-500/20 border-0 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Adjust Salary
                    </button>
                </div>
                
                @if($employee->payrolls->count() > 0)
                    <div class="divide-y divide-surface-100 dark:divide-surface-700/50">
                        @foreach($employee->payrolls->sortByDesc('created_at')->take(5) as $pay)
                            <div class="p-6 flex items-center justify-between hover:bg-surface-50 dark:hover:bg-surface-800/30 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-surface-100 dark:bg-surface-700 flex items-center justify-center text-surface-500 dark:text-surface-400 border border-surface-200 dark:border-surface-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-surface-900 dark:text-white">{{ date('F Y', strtotime($pay->month.'-01')) }} Salary</h4>
                                        <p class="text-xs text-surface-500 mt-0.5">Basic: ${{ number_format($pay->basic_salary,2) }} &bull; Net: ${{ number_format($pay->net_salary,2) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase {{ $pay->status === 'paid' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' }}">
                                        {{ $pay->status }}
                                    </span>
                                    <a href="{{ route('admin.payroll.show', $pay) }}" class="p-2 text-surface-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors bg-surface-50 hover:bg-surface-100 dark:bg-surface-900/50 dark:hover:bg-surface-700 rounded-lg border border-surface-200 dark:border-surface-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center text-surface-500">
                        No payroll records generated yet.
                    </div>
                @endif
            </div>
            
            {{-- Attendance Overview --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
                <div class="px-6 py-5 border-b border-surface-200 dark:border-surface-700">
                    <h3 class="text-base font-bold text-surface-900 dark:text-white">Recent Attendance</h3>
                    <p class="text-xs text-surface-500 mt-1">Last 7 recorded shifts</p>
                </div>
                @if($employee->attendances->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-surface-50 dark:bg-surface-900/30">
                                <tr>
                                    <th class="px-6 py-3 text-[10px] font-bold text-surface-500 uppercase tracking-widest">Date</th>
                                    <th class="px-6 py-3 text-[10px] font-bold text-surface-500 uppercase tracking-widest">Clock In</th>
                                    <th class="px-6 py-3 text-[10px] font-bold text-surface-500 uppercase tracking-widest">Clock Out</th>
                                    <th class="px-6 py-3 text-[10px] font-bold text-surface-500 uppercase tracking-widest text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                                @foreach($employee->attendances->sortByDesc('date')->take(7) as $att)
                                    <tr class="hover:bg-surface-50 dark:hover:bg-surface-800/20">
                                        <td class="px-6 py-3 text-sm font-medium text-surface-800 dark:text-surface-200">{{ \Carbon\Carbon::parse($att->date)->format('M d, Y') }}</td>
                                        <td class="px-6 py-3 text-sm text-surface-500">{{ $att->clock_in ? \Carbon\Carbon::parse($att->clock_in)->format('h:i A') : '-' }}</td>
                                        <td class="px-6 py-3 text-sm text-surface-500">{{ $att->clock_out ? \Carbon\Carbon::parse($att->clock_out)->format('h:i A') : '-' }}</td>
                                        <td class="px-6 py-3 text-right">
                                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase {{ $att->status === 'present' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : ($att->status === 'half-day' ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400') }}">
                                                {{ $att->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center text-surface-500">
                        No attendance records found.
                    </div>
                @endif
            </div>
            
        </div>
    </div>
</x-layouts.admin>

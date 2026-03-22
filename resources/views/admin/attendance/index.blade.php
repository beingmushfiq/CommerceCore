<x-layouts.admin>
    <x-slot:header>Attendance Tracking</x-slot:header>

    <div class="space-y-6">
        {{-- Clock In/Out Section --}}
        <div class="bg-white dark:bg-surface-800 p-8 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h3 class="text-xl font-bold text-surface-800 dark:text-white mb-1">Daily Attendance</h3>
                <p class="text-sm text-surface-400">Current Time: <span class="font-bold text-primary-500">{{ now()->format('h:i A') }}</span></p>
                @if($todayAttendance)
                    <p class="mt-4 inline-flex items-center text-xs font-bold text-green-500 bg-green-50 dark:bg-green-900/20 px-3 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                        Active Session: Started at {{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('h:i A') }}
                    </p>
                @endif
            </div>

            <div class="flex gap-4">
                @if(!$todayAttendance)
                    <form action="{{ route('admin.attendance.clock_in') }}" method="POST">
                        @csrf
                        <button type="submit" class="group relative px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-black rounded-2xl transition-all shadow-xl shadow-green-500/20 active:scale-95 flex items-center gap-3">
                            <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            CLOCK IN
                        </button>
                    </form>
                @elseif(!$todayAttendance->clock_out)
                    <form action="{{ route('admin.attendance.clock_out') }}" method="POST">
                        @csrf
                        <button type="submit" class="group relative px-8 py-4 bg-rose-600 hover:bg-rose-700 text-white font-black rounded-2xl transition-all shadow-xl shadow-rose-500/20 active:scale-95 flex items-center gap-3">
                            <svg class="w-6 h-6 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            CLOCK OUT
                        </button>
                    </form>
                @else
                    <div class="px-8 py-4 bg-surface-100 dark:bg-surface-700/50 text-surface-400 font-black rounded-2xl border-2 border-dashed border-surface-200 dark:border-surface-600">
                        SHIFT ENDED
                    </div>
                @endif
            </div>
        </div>

        {{-- Attendance Logs --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700">
                <h3 class="text-sm font-bold text-surface-800 dark:text-white uppercase tracking-wider">Attendance Logs</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-50 dark:bg-surface-900/30 border-b border-surface-200 dark:border-surface-700">
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase">Staff Member</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase">Date</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-center">Clock In</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-center">Clock Out</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-center">Working Hours</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-surface-500 uppercase text-right">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100 dark:divide-surface-700">
                        @foreach($attendances as $log)
                        <tr class="hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 font-bold text-xs mr-3">
                                        {{ substr($log->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-surface-800 dark:text-white">{{ $log->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-surface-500">
                                {{ $log->date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-bold text-green-600">{{ \Carbon\Carbon::parse($log->clock_in)->format('h:i A') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($log->clock_out)
                                    <span class="text-xs font-bold text-rose-500">{{ \Carbon\Carbon::parse($log->clock_out)->format('h:i A') }}</span>
                                @else
                                    <span class="text-[10px] font-bold text-surface-400 italic">Expected...</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($log->clock_out)
                                    @php
                                        $in = \Carbon\Carbon::parse($log->clock_in);
                                        $out = \Carbon\Carbon::parse($log->clock_out);
                                        $duration = $out->diffInMinutes($in);
                                        $hours = floor($duration / 60);
                                        $mins = $duration % 60;
                                    @endphp
                                    <span class="text-xs font-bold text-surface-700 dark:text-surface-300">{{ $hours }}h {{ $mins }}m</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-[10px] text-surface-400">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>

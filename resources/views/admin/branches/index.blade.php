<x-layouts.admin>
    <x-slot:header>Branch Management</x-slot:header>

    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-black text-surface-900 dark:text-white uppercase tracking-tighter">Store Locations</h2>
            <a href="{{ route('admin.branches.create') }}" class="px-6 py-2.5 bg-indigo-600 text-white text-xs font-black rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20 active:scale-95">
                ADD NEW BRANCH
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($branches as $branch)
            <div class="bg-white dark:bg-surface-800 rounded-3xl p-6 border border-surface-200 dark:border-surface-700 shadow-sm relative overflow-hidden group">
                @if($branch->is_primary)
                <div class="absolute top-0 right-0 px-4 py-1.5 bg-indigo-600 text-[8px] font-black text-white uppercase tracking-widest rounded-bl-xl">PRIMARY</div>
                @endif

                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>

                <h3 class="text-lg font-black text-surface-900 dark:text-white mb-1">{{ $branch->name }}</h3>
                <p class="text-xs text-surface-500 mb-4">{{ $branch->address }}</p>

                <div class="space-y-2 border-t border-surface-100 dark:border-surface-700 pt-4 mb-6">
                    <div class="flex items-center text-[10px] text-surface-400">
                        <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        {{ $branch->phone ?? 'N/A' }}
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('admin.branches.edit', $branch) }}" class="flex-1 py-2 text-center text-[10px] font-black uppercase text-surface-600 bg-surface-50 dark:bg-surface-700 dark:text-surface-300 rounded-xl hover:bg-surface-100 transition-colors">Edit</a>
                    <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <button class="w-full py-2 text-[10px] font-black uppercase text-rose-600 bg-rose-50 dark:bg-rose-900/20 rounded-xl hover:bg-rose-100 transition-colors text-center">Delete</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-layouts.admin>

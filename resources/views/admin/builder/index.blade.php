<x-layouts.admin>
    <x-slot:header>Page Builder</x-slot:header>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-bold text-surface-800 dark:text-white">Page Builder</h2>
                <p class="text-sm text-surface-500 mt-1">Design your storefront pages visually</p>
            </div>
            <a href="{{ route('admin.builder.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/25 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Page
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($pages as $page)
            <div class="card-hover bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden">
                <div class="p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-base font-display font-semibold text-surface-800 dark:text-white">{{ $page->page_name }}</h3>
                            <p class="text-xs text-surface-400 mt-1">/{{ $page->slug }}</p>
                        </div>
                        @if($page->is_homepage)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">Home</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-4 mt-4 text-xs text-surface-400">
                        <span>{{ $page->sections_count }} sections</span>
                        <span>{{ $page->is_published ? 'Published' : 'Draft' }}</span>
                    </div>
                </div>
                <div class="flex border-t border-surface-200 dark:border-surface-700">
                    <a href="{{ route('admin.builder.edit', $page) }}" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-primary-600 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    <a href="{{ route('admin.builder.preview', $page) }}" target="_blank" class="flex-1 flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-surface-600 dark:text-surface-400 hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors border-l border-surface-200 dark:border-surface-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Preview
                    </a>
                    <form method="POST" action="{{ route('admin.builder.delete', $page) }}" onsubmit="return confirm('Delete this page?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors border-l border-surface-200 dark:border-surface-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <svg class="w-16 h-16 mx-auto text-surface-300 dark:text-surface-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"/></svg>
                <p class="text-surface-500 dark:text-surface-400 mb-4">No pages created yet</p>
                <a href="{{ route('admin.builder.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/25">Create your first page →</a>
            </div>
            @endforelse
        </div>
    </div>
</x-layouts.admin>

<x-layouts.admin>
    <x-slot:header>Categories</x-slot:header>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-display font-bold text-surface-800 dark:text-white">Categories</h2>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Add Category Form --}}
            <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-5">
                <h3 class="text-sm font-semibold text-surface-500 uppercase tracking-wider mb-4">New Category</h3>
                <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Name *</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Parent</label>
                        <select name="parent_id" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                            <option value="">None (Top Level)</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full px-5 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/25">Add Category</button>
                </form>
            </div>
            {{-- Category List --}}
            <div class="lg:col-span-2 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden">
                <div class="divide-y divide-surface-100 dark:divide-surface-700">
                    @forelse($categories as $category)
                    <div class="px-5 py-4 flex items-center justify-between hover:bg-surface-50 dark:hover:bg-surface-700/30 transition-colors">
                        <div>
                            <p class="text-sm font-semibold text-surface-800 dark:text-white">{{ $category->name }}</p>
                            <p class="text-xs text-surface-400">{{ $category->products_count ?? $category->products()->count() }} products</p>
                            @if($category->children->count())
                            <div class="mt-2 ml-4 space-y-1">
                                @foreach($category->children as $child)
                                <p class="text-xs text-surface-500 dark:text-surface-400">↳ {{ $child->name }}</p>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-surface-400 hover:text-red-600 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="px-5 py-12 text-center text-surface-500">No categories yet</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

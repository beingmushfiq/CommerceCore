<x-layouts.admin>
    <x-slot:header>Edit Page — {{ $page->page_name }}</x-slot:header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Section Types Panel (Left) --}}
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden sticky top-6">
                <div class="px-5 py-4 border-b border-surface-200 dark:border-surface-700">
                    <h3 class="text-sm font-semibold text-surface-500 uppercase tracking-wider">Add Section</h3>
                </div>
                <div class="p-3 space-y-2">
                    @foreach($sectionTypes as $type => $info)
                    <form method="POST" action="{{ route('admin.builder.sections.add', $page) }}">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors group">
                            <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center flex-shrink-0 group-hover:bg-primary-100 dark:group-hover:bg-primary-900/50 transition-colors">
                                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-surface-800 dark:text-white">{{ $info['label'] }}</p>
                                <p class="text-xs text-surface-400 line-clamp-1">{{ $info['description'] }}</p>
                            </div>
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Sections List (Center) --}}
        <div class="lg:col-span-9 space-y-4">
            @forelse($page->sections as $section)
            <div class="builder-section bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden" x-data="{ editing: false }">
                {{-- Section Header --}}
                <div class="flex items-center justify-between px-5 py-3 bg-surface-50 dark:bg-surface-700/50">
                    <div class="flex items-center gap-3">
                        <div class="cursor-move text-surface-400 hover:text-surface-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">{{ ucfirst(str_replace('_', ' ', $section->type)) }}</span>
                        @if(!$section->is_active)
                        <span class="text-xs text-surface-400">(Hidden)</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-1">
                        <button @click="editing = !editing" class="p-2 text-surface-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('admin.builder.sections.toggle', $section) }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-surface-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors" title="{{ $section->is_active ? 'Hide' : 'Show' }}">
                                @if($section->is_active)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                @endif
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.builder.sections.delete', $section) }}" onsubmit="return confirm('Delete this section?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-surface-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Section Content Editor --}}
                <div x-show="editing" x-transition class="p-5 border-t border-surface-200 dark:border-surface-700">
                    <form method="POST" action="{{ route('admin.builder.sections.update', $section) }}">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($section->contents as $content)
                            <div class="{{ in_array($content->key, ['content', 'subtitle']) ? 'md:col-span-2' : '' }}">
                                <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1.5">{{ ucfirst(str_replace('_', ' ', $content->key)) }}</label>
                                @if(in_array($content->key, ['content']))
                                <textarea name="{{ $content->key }}" rows="4" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">{{ $content->value }}</textarea>
                                @else
                                <input type="text" name="{{ $content->key }}" value="{{ $content->value }}" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <div class="flex items-center gap-3 mt-4">
                            <button type="submit" class="px-5 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-semibold rounded-xl transition-colors">Save Changes</button>
                            <button type="button" @click="editing = false" class="px-5 py-2 bg-surface-100 dark:bg-surface-700 hover:bg-surface-200 dark:hover:bg-surface-600 text-sm font-medium rounded-xl transition-colors dark:text-white">Cancel</button>
                        </div>
                    </form>
                </div>

                {{-- Section Preview --}}
                <div x-show="!editing" class="p-5 border-t border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/30">
                    @if($section->type === 'hero')
                    <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-xl p-8 text-white text-center">
                        <h2 class="text-2xl font-display font-bold">{{ $section->getContent('title') }}</h2>
                        <p class="text-primary-200 mt-2">{{ $section->getContent('subtitle') }}</p>
                        <span class="inline-block mt-4 px-6 py-2 bg-white/20 rounded-lg text-sm font-semibold">{{ $section->getContent('button_text') }}</span>
                    </div>
                    @elseif($section->type === 'banner')
                    <div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl p-6 text-white">
                        <h3 class="text-xl font-display font-bold">{{ $section->getContent('title') }}</h3>
                        <p class="text-amber-100 mt-1">{{ $section->getContent('subtitle') }}</p>
                    </div>
                    @elseif($section->type === 'product_grid')
                    <div class="text-center">
                        <h3 class="text-lg font-display font-semibold text-surface-800 dark:text-white">{{ $section->getContent('title') }}</h3>
                        <p class="text-sm text-surface-400 mt-1">{{ $section->getContent('subtitle') }}</p>
                        <div class="grid grid-cols-4 gap-3 mt-4">
                            @for($i = 0; $i < 4; $i++)
                            <div class="bg-surface-200 dark:bg-surface-700 rounded-xl h-24 flex items-center justify-center text-surface-400 text-xs">Product {{ $i + 1 }}</div>
                            @endfor
                        </div>
                    </div>
                    @elseif($section->type === 'text_block')
                    <div>
                        <h3 class="text-lg font-display font-semibold text-surface-800 dark:text-white">{{ $section->getContent('title') }}</h3>
                        <p class="text-sm text-surface-600 dark:text-surface-300 mt-2">{{ $section->getContent('content') }}</p>
                    </div>
                    @elseif($section->type === 'cta')
                    <div class="bg-gradient-to-r from-primary-500 to-purple-600 rounded-xl p-6 text-white text-center">
                        <h3 class="text-xl font-display font-bold">{{ $section->getContent('title') }}</h3>
                        <p class="text-primary-200 mt-1">{{ $section->getContent('subtitle') }}</p>
                        <span class="inline-block mt-3 px-6 py-2 bg-white text-primary-600 rounded-lg text-sm font-bold">{{ $section->getContent('button_text') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-16 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700">
                <svg class="w-16 h-16 mx-auto text-surface-300 dark:text-surface-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                <p class="text-surface-500 dark:text-surface-400 mb-2">No sections yet</p>
                <p class="text-sm text-surface-400">Add sections from the panel on the left to build your page</p>
            </div>
            @endforelse
        </div>
    </div>
</x-layouts.admin>

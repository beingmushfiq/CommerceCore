<section class="py-24 bg-surface-50 dark:bg-surface-900 overflow-hidden relative">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2240%22%20height%3D%2240%22%20viewBox%3D%220%200%2040%2040%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M20%2020.5V18H0v-2h20v-2H0v-2h20v-2H0V8h20V6H0V4h20V2H0V0h22v20h2V0h2v20h2V0h2v20h2V0h2v20h2V0h2v20h2V0h2v20h2V0h2v20.5h-20z%22%20fill%3D%22currentColor%22%20fill-opacity%3D%220.02%22%20fill-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E')]"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-3xl lg:text-4xl font-display font-extrabold text-surface-900 dark:text-white tracking-tight">{{ $section->getContent('title') }}</h2>
            <p class="mt-4 text-surface-500 lg:text-lg">{{ $section->getContent('subtitle') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Feature 1 --}}
            <div class="relative group bg-white dark:bg-surface-800 p-8 rounded-3xl border border-surface-200 dark:border-surface-700 shadow-xl shadow-surface-200/20 dark:shadow-none hover:-translate-y-2 transition-transform duration-300">
                <div class="absolute -inset-px rounded-3xl border-2 border-transparent group-hover:border-primary-500/50 transition-colors pointer-events-none"></div>
                <div class="w-14 h-14 rounded-2xl bg-primary-50 dark:bg-primary-900/40 flex items-center justify-center mb-6 text-primary-600 dark:text-primary-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-surface-900 dark:text-white">{{ $section->getContent('feature_1_title') }}</h3>
                <p class="mt-3 text-surface-500">{{ $section->getContent('feature_1_desc') }}</p>
            </div>
            {{-- Feature 2 --}}
            <div class="relative group bg-white dark:bg-surface-800 p-8 rounded-3xl border border-surface-200 dark:border-surface-700 shadow-xl shadow-surface-200/20 dark:shadow-none hover:-translate-y-2 transition-transform duration-300">
                <div class="absolute -inset-px rounded-3xl border-2 border-transparent group-hover:border-primary-500/50 transition-colors pointer-events-none"></div>
                <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-900/40 flex items-center justify-center mb-6 text-amber-600 dark:text-amber-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-surface-900 dark:text-white">{{ $section->getContent('feature_2_title') }}</h3>
                <p class="mt-3 text-surface-500">{{ $section->getContent('feature_2_desc') }}</p>
            </div>
            {{-- Feature 3 --}}
            <div class="relative group bg-white dark:bg-surface-800 p-8 rounded-3xl border border-surface-200 dark:border-surface-700 shadow-xl shadow-surface-200/20 dark:shadow-none hover:-translate-y-2 transition-transform duration-300">
                <div class="absolute -inset-px rounded-3xl border-2 border-transparent group-hover:border-primary-500/50 transition-colors pointer-events-none"></div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-900/40 flex items-center justify-center mb-6 text-emerald-600 dark:text-emerald-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-surface-900 dark:text-white">{{ $section->getContent('feature_3_title') }}</h3>
                <p class="mt-3 text-surface-500">{{ $section->getContent('feature_3_desc') }}</p>
            </div>
        </div>
    </div>
</section>

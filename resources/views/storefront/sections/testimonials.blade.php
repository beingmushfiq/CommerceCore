<section class="py-24 bg-white dark:bg-surface-800 relative">
    <div class="absolute inset-y-0 left-0 w-1/2 bg-surface-50 dark:bg-surface-900 rounded-r-3xl"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center md:text-left mb-16 relative z-10">
            <h2 class="text-3xl lg:text-4xl font-display font-bold text-surface-900 dark:text-white tracking-tight">
                {{ $section->getContent('title') }}
            </h2>
            <div class="h-1 w-20 bg-primary-500 rounded-full mt-4 mx-auto md:mx-0"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
            {{-- Testimonial 1 --}}
            <div class="bg-white dark:bg-surface-800 p-8 sm:p-10 rounded-2xl shadow-xl shadow-surface-200/40 dark:shadow-none border border-surface-100 dark:border-surface-700">
                <div class="flex items-center gap-2 mb-6 text-amber-400">
                    @for($i=0; $i<5; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <blockquote class="text-lg sm:text-xl text-surface-600 dark:text-surface-300 italic mb-8 border-l-4 border-primary-400 pl-4">
                    "{{ $section->getContent('review_1') }}"
                </blockquote>
                <div class="flex items-center gap-4 border-t border-surface-100 dark:border-surface-700 pt-6">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center font-bold text-primary-600 dark:text-primary-400 font-display">
                        {{ strtoupper(substr($section->getContent('author_1'), 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-bold text-surface-900 dark:text-white">{{ $section->getContent('author_1') }}</div>
                        <div class="text-sm font-medium text-surface-500">Verified Buyer</div>
                    </div>
                </div>
            </div>

            {{-- Testimonial 2 --}}
            <div class="bg-white dark:bg-surface-800 p-8 sm:p-10 rounded-2xl shadow-xl shadow-surface-200/40 dark:shadow-none border border-surface-100 dark:border-surface-700 md:mt-12">
                <div class="flex items-center gap-2 mb-6 text-amber-400">
                    @for($i=0; $i<5; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <blockquote class="text-lg sm:text-xl text-surface-600 dark:text-surface-300 italic mb-8 border-l-4 border-primary-400 pl-4">
                    "{{ $section->getContent('review_2') }}"
                </blockquote>
                <div class="flex items-center gap-4 border-t border-surface-100 dark:border-surface-700 pt-6">
                    <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center font-bold text-primary-600 dark:text-primary-400 font-display">
                        {{ strtoupper(substr($section->getContent('author_2'), 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-bold text-surface-900 dark:text-white">{{ $section->getContent('author_2') }}</div>
                        <div class="text-sm font-medium text-surface-500">Verified Buyer</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-gradient-to-r from-primary-500 to-purple-600 py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h2 class="text-3xl sm:text-4xl font-display font-bold">{{ $section->getContent('title', 'Ready to get started?') }}</h2>
        <p class="mt-4 text-primary-200 text-lg max-w-xl mx-auto">{{ $section->getContent('subtitle') }}</p>
        @if($section->getContent('button_text'))
        <div class="mt-8">
            <a href="{{ $section->getContent('button_url', '#') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-primary-700 font-display font-bold rounded-2xl shadow-2xl hover:-translate-y-0.5 transition-all">
                {{ $section->getContent('button_text') }}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        @endif
    </div>
</section>

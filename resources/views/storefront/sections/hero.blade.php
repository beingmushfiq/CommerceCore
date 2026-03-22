<section class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 py-24 sm:py-32 overflow-hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.05%22%3E%3Cpath%20d%3D%22M36%2034v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6%2034v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6%204V0H4v4H0v2h4v4h2V6h4V4H6z%22%2F%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E')] opacity-40"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-display font-extrabold text-white leading-tight animate-fade-in">{{ $section->getContent('title', 'Welcome') }}</h1>
        <p class="mt-6 text-xl text-primary-200 max-w-2xl mx-auto animate-slide-up">{{ $section->getContent('subtitle') }}</p>
        @if($section->getContent('button_text'))
        <div class="mt-10 animate-slide-up">
            <a href="{{ $section->getContent('button_url', '#') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-primary-700 font-display font-bold rounded-2xl shadow-2xl hover:-translate-y-0.5 transition-all">
                {{ $section->getContent('button_text') }}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        @endif
    </div>
</section>

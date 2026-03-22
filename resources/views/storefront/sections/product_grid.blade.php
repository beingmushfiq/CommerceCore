<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-display font-bold text-surface-800 dark:text-white">{{ $section->getContent('title', 'Our Products') }}</h2>
        <p class="mt-3 text-surface-500">{{ $section->getContent('subtitle') }}</p>
    </div>
    @php
        $count = (int) $section->getContent('count', 8);
        $gridProducts = \App\Models\Product::where('store_id', $store->id)->where('status', 'active')->where('featured', true)->take($count)->get();
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
        @foreach($gridProducts as $product)
        <a href="{{ route('storefront.product.detail', [$store->slug, $product->slug]) }}" class="group card-hover bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="aspect-square bg-surface-100 dark:bg-surface-700 overflow-hidden">
                @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                <div class="w-full h-full flex items-center justify-center"><svg class="w-12 h-12 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                @endif
            </div>
            <div class="p-4">
                <h3 class="text-sm font-semibold text-surface-800 dark:text-white line-clamp-2">{{ $product->name }}</h3>
                <span class="text-lg font-display font-bold text-primary-600 dark:text-primary-400 mt-1 block">${{ number_format($product->price, 2) }}</span>
            </div>
        </a>
        @endforeach
    </div>
</section>

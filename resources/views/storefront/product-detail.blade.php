<x-layouts.storefront :store="$store">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            {{-- Product Image --}}
            <div class="aspect-square bg-surface-100 dark:bg-surface-800 rounded-3xl overflow-hidden border border-surface-200 dark:border-surface-700">
                @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center"><svg class="w-24 h-24 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                @endif
            </div>

            {{-- Product Details --}}
            <div class="flex flex-col justify-center">
                @if($product->category)
                <a href="{{ route('storefront.products', [$store->slug, 'category' => $product->category_id]) }}" class="text-sm text-primary-600 dark:text-primary-400 font-medium hover:underline mb-2">{{ $product->category->name }}</a>
                @endif
                <h1 class="text-3xl sm:text-4xl font-display font-bold text-surface-800 dark:text-white">{{ $product->name }}</h1>

                <div class="flex items-center gap-3 mt-4">
                    <span class="text-3xl font-display font-bold text-primary-600 dark:text-primary-400">${{ number_format($product->price, 2) }}</span>
                    @if($product->hasDiscount())
                    <span class="text-xl text-surface-400 line-through">${{ number_format($product->compare_price, 2) }}</span>
                    <span class="px-3 py-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-sm font-bold rounded-lg">Save {{ $product->discountPercentage() }}%</span>
                    @endif
                </div>

                <div class="mt-4 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full {{ $product->inStock() ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                    <span class="text-sm font-medium {{ $product->inStock() ? 'text-emerald-600' : 'text-red-500' }}">{{ $product->inStock() ? $product->stock . ' in stock' : 'Out of stock' }}</span>
                </div>

                @if($product->description)
                <div class="mt-6 text-surface-600 dark:text-surface-300 leading-relaxed">{{ $product->description }}</div>
                @endif

                @if($product->inStock())
                <form method="POST" action="{{ route('storefront.cart.add', $store->slug) }}" class="mt-8 space-y-6">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    @if($product->allow_subscription)
                    <div class="p-6 bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-900/50 rounded-2xl">
                        <h4 class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-4 flex items-center gap-2 italic">
                            🚀 Multi-Delivery Subscription
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="purchase_type" value="onetime" checked class="peer sr-only">
                                <div class="p-4 bg-white dark:bg-surface-800 rounded-xl border border-surface-200 dark:border-surface-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-500/20 transition-all group-hover:bg-surface-50 dark:group-hover:bg-surface-700/50">
                                    <p class="text-[10px] font-black text-surface-400 uppercase">One-time Purchase</p>
                                    <p class="text-sm font-black text-surface-900 dark:text-white mt-1">${{ number_format($product->price, 2) }}</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="purchase_type" value="subscription" class="peer sr-only">
                                <div class="p-4 bg-white dark:bg-surface-800 rounded-xl border border-surface-200 dark:border-surface-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-500/20 transition-all group-hover:bg-surface-50 dark:group-hover:bg-surface-700/50">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-[10px] font-black text-indigo-500 uppercase">Subscribe & Save</p>
                                            <p class="text-sm font-black text-surface-900 dark:text-white mt-1">
                                                ${{ number_format($product->price * (1 - $product->subscription_discount_percentage/100), 2) }}
                                            </p>
                                        </div>
                                        <span class="px-2 py-0.5 bg-indigo-500 text-white text-[8px] font-black rounded uppercase">-{{ (int)$product->subscription_discount_percentage }}%</span>
                                    </div>
                                    <p class="text-[8px] text-indigo-400 font-bold mt-2 uppercase">Every {{ $product->subscription_interval }}</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center gap-4">
                        <div class="flex items-center border border-surface-200 dark:border-surface-700 rounded-xl overflow-hidden">
                            <button type="button" onclick="this.nextElementSibling.stepDown()" class="px-4 py-3 text-surface-500 hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">−</button>
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-16 text-center border-0 bg-transparent text-sm font-semibold dark:text-white focus:ring-0">
                            <button type="button" onclick="this.previousElementSibling.stepUp()" class="px-4 py-3 text-surface-500 hover:bg-surface-50 dark:hover:bg-surface-700 transition-colors">+</button>
                        </div>
                        <button type="submit" class="flex-1 px-8 py-3.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-display font-bold rounded-xl shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Add to Cart
                        </button>
                    </div>
                </form>
                @endif

                @if($product->sku)
                <p class="mt-6 text-[10px] text-surface-400 font-bold uppercase tracking-widest bg-surface-50 dark:bg-surface-800 inline-block px-3 py-1 rounded-lg">SKU: {{ $product->sku }}</p>
                @endif

                {{-- Branch Availability --}}
                @if($store->branches->count() > 1)
                <div class="mt-8 pt-8 border-t border-surface-100 dark:border-surface-800">
                    <h4 class="text-[10px] font-black text-surface-400 uppercase tracking-widest mb-4 flex items-center gap-2 italic">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        In-Store Availability
                    </h4>
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($store->branches as $branch)
                        @php $inventory = $branch->inventories()->where('product_id', $product->id)->first(); @endphp
                        <div class="flex justify-between items-center p-4 bg-surface-50 dark:bg-surface-800/50 rounded-2xl border border-surface-100 dark:border-surface-700 hover:border-indigo-500/30 transition-all">
                            <div>
                                <p class="text-[10px] font-black text-surface-900 dark:text-white uppercase">{{ $branch->name }}</p>
                                <p class="text-[8px] text-surface-400 font-bold uppercase tracking-widest">{{ \Illuminate\Support\Str::limit($branch->address, 30) }}</p>
                            </div>
                            <span class="px-3 py-1 text-[8px] font-black rounded-full uppercase tracking-widest {{ ($inventory->stock ?? 0) > 0 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400' }}">
                                {{ ($inventory->stock ?? 0) > 0 ? 'IN STOCK' : 'OUT' }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Reviews Section --}}
        @include('storefront.partials.reviews', ['product' => $product, 'storeSlug' => $store->slug])

        {{-- Related Products --}}
        @if($relatedProducts->count())
        <div class="mt-20">
            <h2 class="text-2xl font-display font-bold text-surface-800 dark:text-white mb-8">Related Products</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-5">
                @foreach($relatedProducts as $related)
                <a href="{{ route('storefront.product.detail', ['store' => $store->slug, 'product' => $related->slug]) }}" class="group card-hover bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden">
                    <div class="aspect-square bg-surface-100 dark:bg-surface-700 overflow-hidden">
                        @if($related->image)
                        <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                        <div class="w-full h-full flex items-center justify-center"><svg class="w-10 h-10 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-surface-800 dark:text-white line-clamp-2">{{ $related->name }}</h3>
                        <span class="text-lg font-display font-bold text-primary-600 dark:text-primary-400 mt-1 block">${{ number_format($related->price, 2) }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</x-layouts.storefront>

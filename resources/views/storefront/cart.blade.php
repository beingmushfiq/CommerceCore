<x-layouts.storefront :store="$storeModel">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-display font-bold text-surface-800 dark:text-white mb-8">Shopping Cart</h1>

        @if(count($items))
        <div class="space-y-4">
            @foreach($items as $item)
            <div class="flex items-center gap-4 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-4">
                <div class="w-20 h-20 rounded-xl bg-surface-100 dark:bg-surface-700 overflow-hidden flex-shrink-0">
                    @if($item['product']->image)
                    <img src="{{ asset('storage/' . $item['product']->image) }}" alt="" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center"><svg class="w-8 h-8 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-surface-800 dark:text-white">{{ $item['product']->name }}</h3>
                    <p class="text-sm text-primary-600 dark:text-primary-400 font-display font-bold mt-1">${{ number_format($item['product']->price, 2) }}</p>
                </div>
                <form method="POST" action="{{ route('storefront.cart.update', $store) }}" class="flex items-center gap-2">
                    @csrf @method('PUT')
                    <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" class="w-16 text-center px-2 py-2 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                    <button type="submit" class="px-3 py-2 bg-surface-100 dark:bg-surface-700 hover:bg-surface-200 dark:hover:bg-surface-600 rounded-xl text-xs font-medium transition-colors dark:text-white">Update</button>
                </form>
                <div class="text-right">
                    <p class="text-sm font-bold text-surface-800 dark:text-white">${{ number_format($item['total'], 2) }}</p>
                    <form method="POST" action="{{ route('storefront.cart.remove', [$store, $item['product']->id]) }}" class="mt-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:underline">Remove</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8 bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-lg font-display font-semibold text-surface-800 dark:text-white">Total</span>
                <span class="text-2xl font-display font-bold gradient-text">${{ number_format($total, 2) }}</span>
            </div>
            <a href="{{ route('storefront.checkout', $store) }}" class="w-full inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-display font-bold rounded-xl shadow-lg shadow-primary-500/25 transition-all">
                Proceed to Checkout
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        @else
        <div class="text-center py-20">
            <svg class="w-20 h-20 mx-auto text-surface-300 dark:text-surface-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <h2 class="text-xl font-display font-semibold text-surface-800 dark:text-white mb-2">Your cart is empty</h2>
            <p class="text-surface-500 mb-6">Start shopping to add items to your cart</p>
            <a href="{{ route('storefront.products', $store) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-xl transition-colors">Browse Products</a>
        </div>
        @endif
    </div>
</x-layouts.storefront>

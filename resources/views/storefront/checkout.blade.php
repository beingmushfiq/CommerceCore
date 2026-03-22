<x-layouts.storefront :store="$storeModel">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-display font-bold text-surface-800 dark:text-white mb-8">Checkout</h1>

        <form method="POST" action="{{ route('storefront.checkout.place', $store) }}">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Customer Info --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6">
                        <h2 class="text-lg font-display font-semibold text-surface-800 dark:text-white mb-5">Shipping Information</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Full Name *</label>
                                <input type="text" name="customer_name" required value="{{ old('customer_name', auth()->user()?->name) }}" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                                @error('customer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Email *</label>
                                <input type="email" name="customer_email" required value="{{ old('customer_email', auth()->user()?->email) }}" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Phone</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Shipping Address *</label>
                                <textarea name="address" required rows="3" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">{{ old('address') }}</textarea>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Notes</label>
                                <textarea name="notes" rows="2" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white" placeholder="Special instructions...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div>
                    <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6 sticky top-24">
                        <h2 class="text-lg font-display font-semibold text-surface-800 dark:text-white mb-5">Order Summary</h2>
                        <div class="space-y-3 mb-4">
                            @foreach($items as $item)
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex-1">
                                    <p class="font-medium text-surface-800 dark:text-white line-clamp-1">{{ $item['product']->name }}</p>
                                    <p class="text-xs text-surface-400">× {{ $item['quantity'] }}</p>
                                </div>
                                <span class="font-semibold text-surface-800 dark:text-white ml-4">${{ number_format($item['total'], 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="border-t border-surface-200 dark:border-surface-700 pt-4 space-y-2">
                            <div class="flex justify-between text-sm"><span class="text-surface-500">Subtotal</span><span class="font-medium text-surface-800 dark:text-white">${{ number_format($total, 2) }}</span></div>
                            <div class="flex justify-between text-sm"><span class="text-surface-500">Shipping</span><span class="font-medium text-emerald-600">Free</span></div>
                            <div class="flex justify-between text-lg border-t border-surface-200 dark:border-surface-700 pt-3 mt-3">
                                <span class="font-display font-bold text-surface-800 dark:text-white">Total</span>
                                <span class="font-display font-bold gradient-text">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                        <button type="submit" class="w-full mt-6 px-8 py-3.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-display font-bold rounded-xl shadow-lg shadow-primary-500/25 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Place Order
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.storefront>

<x-layouts.admin>
    <x-slot:header>{{ isset($product) ? 'Edit Product' : 'Add Product' }}</x-slot:header>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700">
                <h2 class="text-lg font-display font-semibold text-surface-800 dark:text-white">{{ isset($product) ? 'Edit Product' : 'New Product' }}</h2>
            </div>
            <form method="POST" action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @if(isset($product)) @method('PUT') @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
                               class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Price *</label>
                        <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" step="0.01" required
                               class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Compare Price</label>
                        <input type="number" name="compare_price" value="{{ old('compare_price', $product->compare_price ?? '') }}" step="0.01"
                               class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}"
                               class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Stock *</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required
                               class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Category</label>
                        <select name="category_id" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                            <option value="">No Category</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                            <option value="active" {{ old('status', $product->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="draft" {{ old('status', $product->status ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="archived" {{ old('status', $product->status ?? '') === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    <div class="md:col-span-2" x-data="{ generating: false }">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-sm font-medium text-surface-700 dark:text-surface-300">Description</label>
                            <button type="button" @click="
                                generating = true;
                                fetch('{{ route('ai.generate') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                    },
                                    body: JSON.stringify({
                                        name: document.querySelector('input[name=name]').value,
                                        category: document.querySelector('select[name=category_id] option:checked').text,
                                        tags: 'product, commerce, ' + document.querySelector('input[name=name]').value
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if(data.description) {
                                        document.querySelector('textarea[name=description]').value = data.description;
                                    }
                                })
                                .finally(() => { generating = false; });
                            " class="text-xs font-semibold px-3 py-1 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg shadow flex items-center gap-1 hover:brightness-110 transition disabled:opacity-50">
                                <svg x-show="!generating" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                <svg x-show="generating" class="animate-spin w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span x-text="generating ? 'Thinking...' : 'Generate with AI ✨'"></span>
                            </button>
                        </div>
                        <textarea name="description" rows="5" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Product Image</label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100">
                    </div>

                    <div class="md:col-span-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="featured" value="1" {{ old('featured', $product->featured ?? false) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded-lg border-surface-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm font-medium text-surface-700 dark:text-surface-300">Featured Product</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-surface-200 dark:border-surface-700">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/25 transition-all">
                        {{ isset($product) ? 'Update Product' : 'Create Product' }}
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 bg-surface-100 dark:bg-surface-700 hover:bg-surface-200 dark:hover:bg-surface-600 text-sm font-medium rounded-xl transition-colors dark:text-white">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>

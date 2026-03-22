<x-layouts.admin>
    <x-slot:header>Store Settings — {{ $store->name }}</x-slot:header>
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6">
            <h2 class="text-lg font-display font-semibold text-surface-800 dark:text-white mb-6">Store Identity & Theme</h2>
            <form method="POST" action="{{ route('admin.stores.settings.update', $store) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Shop/Company Name</label>
                    <input type="text" name="name" value="{{ old('name', $store->name) }}" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Company Logo</label>
                    <div class="flex items-center gap-4">
                        @if($store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="Logo" class="w-12 h-12 object-contain rounded-lg border border-surface-200 dark:border-surface-600 bg-white">
                        @endif
                        <input type="file" name="logo" accept="image/*" class="w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:text-surface-300 dark:file:bg-primary-900/50 dark:file:text-primary-400">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">E-commerce Logo (Storefront)</label>
                    <div class="flex items-center gap-4">
                        @if($store->settings?->getSetting('ecom_logo'))
                            <img src="{{ asset('storage/' . $store->settings->getSetting('ecom_logo')) }}" alt="Ecom Logo" class="w-12 h-12 object-contain rounded-lg border border-surface-200 dark:border-surface-600 bg-white">
                        @endif
                        <input type="file" name="ecom_logo" accept="image/*" class="w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:text-surface-300 dark:file:bg-primary-900/50 dark:file:text-primary-400">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Favicon</label>
                    <div class="flex items-center gap-4">
                        @if($store->settings?->getSetting('favicon'))
                            <img src="{{ asset('storage/' . $store->settings->getSetting('favicon')) }}" alt="Favicon" class="w-8 h-8 object-contain rounded border border-surface-200 dark:border-surface-600 bg-white">
                        @endif
                        <input type="file" name="favicon" accept="image/png, image/x-icon, image/jpeg" class="w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:text-surface-300 dark:file:bg-primary-900/50 dark:file:text-primary-400">
                    </div>
                </div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Primary Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="primary_color" value="{{ $store->settings?->getSetting('primary_color', '#4F46E5') }}" class="w-12 h-10 rounded-lg border-0 cursor-pointer">
                        <input type="text" value="{{ $store->settings?->getSetting('primary_color', '#4F46E5') }}" class="flex-1 px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white" readonly>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Secondary Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="secondary_color" value="{{ $store->settings?->getSetting('secondary_color', '#7C3AED') }}" class="w-12 h-10 rounded-lg border-0 cursor-pointer">
                        <input type="text" value="{{ $store->settings?->getSetting('secondary_color', '#7C3AED') }}" class="flex-1 px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white" readonly>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-surface-700 dark:text-surface-300 mb-1.5">Font Family</label>
                    <select name="font" class="w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-700 border-0 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 dark:text-white">
                        <option value="Inter" {{ ($store->settings?->getSetting('font') ?? 'Inter') === 'Inter' ? 'selected' : '' }}>Inter</option>
                        <option value="Outfit" {{ $store->settings?->getSetting('font') === 'Outfit' ? 'selected' : '' }}>Outfit</option>
                        <option value="Poppins" {{ $store->settings?->getSetting('font') === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                    </select>
                </div>
                <button type="submit" class="w-full px-5 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-500/25">Save Settings</button>
            </form>
        </div>
    </div>
</x-layouts.admin>

<x-layouts.admin>
    <x-slot:header>Store Settings — {{ $store->name }}</x-slot:header>
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6">
            <h2 class="text-lg font-display font-semibold text-surface-800 dark:text-white mb-6">Theme & Customization</h2>
            <form method="POST" action="{{ route('admin.stores.settings.update', $store) }}" class="space-y-5">
                @csrf @method('PUT')
                <div>
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

<section class="py-16 bg-white dark:bg-surface-900 border-t border-surface-100 dark:border-surface-800">
    <div class="max-w-4xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-black text-surface-900 dark:text-white mb-4">Common Questions</h2>
            <p class="text-surface-500">Everything you need to know about our service.</p>
        </div>
        
        <div class="space-y-4">
            @foreach($section->settings['items'] ?? [
                ['q' => 'What is your return policy?', 'a' => 'We offer a 30-day money-back guarantee on all products.'],
                ['q' => 'How long does shipping take?', 'a' => 'Standard shipping typically takes 3-5 business days.'],
                ['q' => 'Do you ship internationally?', 'a' => 'Currently we serve only domestic customers.']
            ] as $item)
            <div class="p-6 bg-surface-50 dark:bg-surface-800 rounded-2xl border border-surface-100 dark:border-surface-700 transition-all hover:shadow-md cursor-pointer group">
                <div class="flex justify-between items-center mb-2">
                    <h4 class="font-bold text-surface-900 dark:text-white">{{ $item['q'] }}</h4>
                    <svg class="w-4 h-4 text-surface-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
                <p class="text-sm text-surface-500 leading-relaxed">{{ $item['a'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

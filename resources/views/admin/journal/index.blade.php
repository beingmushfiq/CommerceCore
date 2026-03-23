<x-layouts.admin>
    <x-slot:header>Journal Entries</x-slot:header>

    <div x-data="journalEntryForm()" class="space-y-6">
        {{-- Header Actions --}}
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-surface-900 dark:text-white">General Journal</h2>
            <button @click="showForm = !showForm" class="btn btn-primary">
                <span x-text="showForm ? 'Cancel Entry' : '+ New Journal Entry'"></span>
            </button>
        </div>

        {{-- Create Journal Entry Form (Toggleable) --}}
        <div x-show="showForm" x-collapse x-cloak class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 p-6 shadow-sm">
            <form action="{{ route('admin.journal.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold uppercase tracking-wider">Date</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white transition-colors focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold uppercase tracking-wider">Reference #</label>
                        <input type="text" name="reference" value="JE-{{ date('Ymd') }}-{{ rand(100,999) }}" required class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white transition-colors focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs text-surface-400 mb-1 font-bold uppercase tracking-wider">Description</label>
                        <input type="text" name="description" placeholder="To record..." class="w-full text-sm border-surface-200 rounded-lg dark:bg-surface-900 dark:text-white transition-colors focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                {{-- Lines --}}
                <div class="border rounded-xl border-surface-200 dark:border-surface-700 overflow-hidden mb-6">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-surface-50 dark:bg-surface-900/50 text-xs font-semibold text-surface-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 w-1/3">Account</th>
                                <th class="px-4 py-3 w-1/3">Line Description</th>
                                <th class="px-4 py-3 text-right w-32">Debit</th>
                                <th class="px-4 py-3 text-right w-32">Credit</th>
                                <th class="px-4 py-3 text-center w-16"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(line, index) in lines" :key="line.id">
                                <tr class="border-b border-surface-100 dark:border-surface-700/50 last:border-0 hover:bg-surface-50 dark:hover:bg-surface-800/30 transition-colors group">
                                    <td class="px-4 py-2">
                                        <select :name="'lines['+index+'][account_id]'" x-model="line.account_id" required class="w-full text-sm border-0 bg-transparent py-1.5 focus:ring-0 dark:text-white">
                                            <option value="">Select Account...</option>
                                            @foreach($accounts as $acc)
                                                <option value="{{ $acc->id }}">{{ $acc->code ? $acc->code.' - ' : '' }}{{ $acc->name }} ({{ ucfirst($acc->gl_type) }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="text" :name="'lines['+index+'][description]'" x-model="line.description" placeholder="Line note..." class="w-full text-sm border-0 bg-transparent py-1.5 focus:ring-0 dark:text-white placeholder-surface-300">
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" step="0.01" min="0" :name="'lines['+index+'][debit]'" x-model.number="line.debit" @input="line.credit = line.debit > 0 ? 0 : line.credit" class="w-full text-sm text-right border-0 bg-transparent py-1.5 focus:ring-0 dark:text-white placeholder-surface-300" placeholder="0.00">
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" step="0.01" min="0" :name="'lines['+index+'][credit]'" x-model.number="line.credit" @input="line.debit = line.credit > 0 ? 0 : line.debit" class="w-full text-sm text-right border-0 bg-transparent py-1.5 focus:ring-0 dark:text-white placeholder-surface-300" placeholder="0.00">
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button type="button" @click="removeLine(line.id)" class="p-1.5 text-surface-400 hover:text-rose-500 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors opacity-0 group-hover:opacity-100" x-show="lines.length > 2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="bg-surface-50 dark:bg-surface-900/30 border-t border-surface-200 dark:border-surface-700">
                            <tr>
                                <td colspan="2" class="px-4 py-3">
                                    <button type="button" @click="addLine()" class="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-800 transition-colors">+ Add Line</button>
                                </td>
                                <td class="px-4 py-3 text-right font-bold" :class="totalsMatch ? 'text-emerald-600' : 'text-rose-600'" x-text="formatCurrency(totalDebit)"></td>
                                <td class="px-4 py-3 text-right font-bold" :class="totalsMatch ? 'text-emerald-600' : 'text-rose-600'" x-text="formatCurrency(totalCredit)"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="flex justify-between items-center">
                    <div class="text-sm" :class="totalsMatch ? 'text-emerald-500' : 'text-rose-500'">
                        <span x-show="!totalsMatch">⚠ Debits and Credits must be equal to save.</span>
                        <span x-show="totalsMatch && totalDebit > 0">✓ Entry is balanced.</span>
                    </div>
                    <button type="submit" class="btn btn-primary" :disabled="!totalsMatch || totalDebit === 0">Save Journal Entry</button>
                </div>
            </form>
        </div>

        {{-- Journal Entries List --}}
        <div class="bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-surface-50 dark:bg-surface-900/50 text-xs font-semibold text-surface-500 uppercase tracking-wider border-b border-surface-200 dark:border-surface-700">
                        <tr>
                            <th class="px-6 py-4">Date & Ref</th>
                            <th class="px-6 py-4 w-1/2">Details</th>
                            <th class="px-6 py-4 text-right">Debit</th>
                            <th class="px-6 py-4 text-right">Credit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100 dark:divide-surface-700/50">
                        @forelse($entries as $entry)
                            <tr class="hover:bg-surface-50 dark:hover:bg-surface-800/30 transition-colors">
                                <td class="px-6 py-4 align-top">
                                    <div class="font-bold text-surface-900 dark:text-white">{{ $entry->date->format('M d, Y') }}</div>
                                    <div class="text-xs text-surface-500 font-mono mt-1">{{ $entry->reference }}</div>
                                    @if($entry->description)
                                        <div class="text-xs text-surface-400 mt-2 italic flex max-w-xs">{{ $entry->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-top p-0 space-y-1">
                                    <div class="flex flex-col gap-1 my-3">
                                        @foreach($entry->lines as $line)
                                            <div class="flex justify-between text-xs py-1 px-4 {{ $line->credit > 0 ? 'pl-8' : '' }} hover:bg-surface-100 dark:hover:bg-surface-700/30 rounded">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-medium text-surface-700 dark:text-surface-300">{{ $line->account->name }}</span>
                                                    @if($line->description)
                                                        <span class="text-surface-400"> - {{ $line->description }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top text-right">
                                    <div class="flex flex-col gap-1 mt-3">
                                        @foreach($entry->lines as $line)
                                            <div class="text-xs py-1 {{ $line->debit > 0 ? 'font-medium text-surface-900 dark:text-white' : 'text-transparent' }}">
                                                {{ $line->debit > 0 ? number_format($line->debit, 2) : '-' }}
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top text-right">
                                    <div class="flex flex-col gap-1 mt-3">
                                        @foreach($entry->lines as $line)
                                            <div class="text-xs py-1 {{ $line->credit > 0 ? 'font-medium text-surface-900 dark:text-white' : 'text-transparent' }}">
                                                {{ $line->credit > 0 ? number_format($line->credit, 2) : '-' }}
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-surface-500">
                                    No journal entries found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($entries->hasPages())
                <div class="p-4 border-t border-surface-200 dark:border-surface-700">
                    {{ $entries->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('journalEntryForm', () => ({
                showForm: false,
                lines: [
                    { id: Date.now(), account_id: '', description: '', debit: '', credit: '' },
                    { id: Date.now() + 1, account_id: '', description: '', debit: '', credit: '' }
                ],
                addLine() {
                    this.lines.push({ id: Date.now(), account_id: '', description: '', debit: '', credit: '' });
                },
                removeLine(id) {
                    if (this.lines.length > 2) {
                        this.lines = this.lines.filter(l => l.id !== id);
                    }
                },
                get totalDebit() {
                    return this.lines.reduce((sum, line) => sum + (parseFloat(line.debit) || 0), 0);
                },
                get totalCredit() {
                    return this.lines.reduce((sum, line) => sum + (parseFloat(line.credit) || 0), 0);
                },
                get totalsMatch() {
                    return Math.abs(this.totalDebit - this.totalCredit) < 0.01;
                },
                formatCurrency(val) {
                    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val);
                }
            }));
        });
    </script>
    @endpush
</x-layouts.admin>

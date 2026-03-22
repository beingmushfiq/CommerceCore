<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>POS - {{ config('app.name', 'CommerceCore') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-surface-900 bg-surface-100 h-screen overflow-hidden">
    
    <div x-data="posSystem()" class="flex h-full w-full">
        <!-- Left Side: Product Grid (70%) -->
        <main class="w-[70%] h-full flex flex-col bg-surface-100">
            <!-- Header Search & Category Filter -->
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 border-b border-surface-200 shrink-0">
                <div class="flex items-center gap-4 w-1/2">
                    <a href="{{ route('admin.dashboard') }}" class="text-surface-500 hover:text-surface-900 p-2 rounded-lg hover:bg-surface-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <h1 class="text-xl font-semibold text-surface-800 tracking-tight">CommerceCore POS</h1>
                </div>
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input x-model="searchQuery" type="text" class="block w-full pl-10 pr-3 py-2 border border-surface-200 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-surface-50 text-surface-900" placeholder="Scan barcode or search products...">
                    </div>
                </div>
            </header>

            <!-- Categories -->
            <div class="h-14 bg-white border-b border-surface-200 flex items-center px-6 gap-3 overflow-x-auto shrink-0 no-scrollbar">
                <button @click="selectedCategory = null" 
                        :class="selectedCategory === null ? 'bg-primary-600 text-white border-transparent' : 'bg-white text-surface-700 border-surface-200 hover:bg-surface-50'"
                        class="px-4 py-1.5 rounded-full text-sm font-medium border transition-colors whitespace-nowrap shadow-sm">
                    All Items
                </button>
                @foreach($categories as $category)
                <button @click="selectedCategory = {{ $category->id }}"
                        :class="selectedCategory === {{ $category->id }} ? 'bg-primary-600 text-white border-transparent' : 'bg-white text-surface-700 border-surface-200 hover:bg-surface-50'"
                        class="px-4 py-1.5 rounded-full text-sm font-medium border transition-colors whitespace-nowrap shadow-sm">
                    {{ $category->name }} ({{ $category->products_count }})
                </button>
                @endforeach
            </div>

            <!-- Product Grid -->
            <div class="flex-1 overflow-y-auto p-6 scroll-smooth">
                <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
                    @foreach($products as $product)
                    <div x-show="matchesSearch('{{ addslashes($product->name) }}', {{ $product->category_id ?? 'null' }})" 
                         @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $product->image ? asset('storage/'.$product->image) : '' }}')"
                         class="bg-white rounded-xl shadow-sm border border-surface-200 overflow-hidden cursor-pointer hover:shadow-md hover:border-primary-300 transition-all group flex flex-col h-48 select-none">
                        
                        @if($product->image)
                        <div class="h-28 w-full bg-surface-100 overflow-hidden">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                        </div>
                        @else
                        <div class="h-28 w-full bg-gradient-to-br from-surface-100 to-surface-200 flex items-center justify-center text-surface-400">
                            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                        
                        <div class="p-3 flex-1 flex flex-col justify-between">
                            <h3 class="text-sm font-semibold text-surface-800 line-clamp-2 leading-tight group-hover:text-primary-600 transition-colors">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-primary-600 font-bold">${{ number_format($product->price, 2) }}</span>
                                <span class="text-xs text-surface-500 font-medium">Stock: {{ $product->stock }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </main>

        <!-- Right Side: Cart & Payment (30%) -->
        <aside class="w-[30%] h-full bg-white shadow-xl z-10 flex flex-col border-l border-surface-200 shrink-0">
            <!-- Customer Selector -->
            <div class="p-4 border-b border-surface-200 bg-surface-50 shrink-0">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-sm font-semibold text-surface-700 uppercase tracking-wider">Current Sale</h2>
                    <button class="text-primary-600 hover:text-primary-800 text-sm font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Customer
                    </button>
                </div>
                <!-- Mini Customer Form -->
                <div class="flex flex-col gap-2">
                    <input type="text" x-model="customer.name" placeholder="Customer Name (Optional)" class="w-full text-sm border-surface-200 rounded-lg focus:ring-primary-500 focus:border-primary-500 py-1.5">
                    <input type="text" x-model="customer.phone" placeholder="Phone Number (Optional)" class="w-full text-sm border-surface-200 rounded-lg focus:ring-primary-500 focus:border-primary-500 py-1.5">
                </div>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-3 bg-surface-50/50">
                <template x-if="cart.length === 0">
                    <div class="flex flex-col items-center justify-center h-full text-surface-400">
                        <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <p class="font-medium text-surface-600 text-lg">Cart is empty</p>
                        <p class="text-sm mt-1">Scan or tap items to add</p>
                    </div>
                </template>

                <template x-for="item in cart" :key="item.id">
                    <div class="bg-white p-3 rounded-xl shadow-sm border border-surface-200 flex items-center gap-3 group relative hover:border-primary-300 transition-colors">
                        <button @click="removeFromCart(item.id)" class="absolute -top-2 -right-2 bg-red-100 text-red-600 rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-200 shadow-sm border border-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>

                        <div class="w-12 h-12 bg-surface-100 rounded-lg overflow-hidden shrink-0 border border-surface-200">
                            <template x-if="item.image">
                                <img :src="item.image" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!item.image">
                                <div class="w-full h-full flex items-center justify-center text-surface-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            </template>
                        </div>

                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-surface-900 truncate" x-text="item.name"></h4>
                            <div class="text-primary-600 font-bold text-sm" x-text="formatCurrency(item.price)"></div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 bg-surface-100 rounded-lg p-1">
                            <button @click="updateQuantity(item.id, item.quantity - 1)" class="w-7 h-7 flex items-center justify-center bg-white rounded shadow-sm text-surface-600 hover:text-surface-900 hover:bg-surface-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                            </button>
                            <span class="w-6 text-center text-sm font-bold text-surface-800" x-text="item.quantity"></span>
                            <button @click="updateQuantity(item.id, item.quantity + 1)" class="w-7 h-7 flex items-center justify-center bg-white rounded shadow-sm text-surface-600 hover:text-surface-900 hover:bg-surface-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Totals & Payment -->
            <div class="bg-white border-t border-surface-200 p-4 shrink-0 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <div class="space-y-2 mb-4 text-sm">
                    <div class="flex justify-between text-surface-500">
                        <span>Subtotal</span>
                        <span x-text="formatCurrency(subtotal)"></span>
                    </div>
                    <div class="flex justify-between text-surface-500">
                        <span>Tax (0%)</span>
                        <span x-text="formatCurrency(tax)"></span>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-surface-900 pt-2 border-t border-surface-200">
                        <span>Total</span>
                        <span class="text-primary-600" x-text="formatCurrency(total)"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <button @click="paymentMethod = 'cash'; openPaymentModal()" :disabled="cart.length === 0" class="disabled:opacity-50 disabled:cursor-not-allowed flex flex-col items-center justify-center gap-2 py-3 px-4 rounded-xl border-2 border-surface-200 hover:border-primary-500 hover:bg-primary-50 transition-all text-surface-700 font-medium">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Cash
                    </button>
                    <button @click="paymentMethod = 'card'; processCardPayment()" :disabled="cart.length === 0" class="disabled:opacity-50 disabled:cursor-not-allowed flex flex-col items-center justify-center gap-2 py-3 px-4 rounded-xl border-2 border-surface-200 hover:border-primary-500 hover:bg-primary-50 transition-all text-surface-700 font-medium">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Card
                    </button>
                </div>
                
                <button @click="clearCart()" :disabled="cart.length === 0" class="w-full py-2 text-surface-500 hover:text-red-600 hover:bg-red-50 rounded-lg text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Clear Sale
                </button>
            </div>
        </aside>

        <!-- Payment Modal (Cash) -->
        <div x-show="isPaymentModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;" x-transition>
            <div @click.away="isPaymentModalOpen = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
                <div class="px-6 py-4 border-b border-surface-200 bg-surface-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-surface-900">Cash Payment</h3>
                    <button @click="isPaymentModalOpen = false" class="text-surface-400 hover:text-surface-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="p-6">
                    <div class="text-center mb-6">
                        <p class="text-surface-500 text-sm font-medium">Total Due</p>
                        <p class="text-4xl font-extrabold text-primary-600" x-text="formatCurrency(total)"></p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-surface-700 mb-2">Amount Tendered</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-surface-500 sm:text-lg">$</span>
                            </div>
                            <input type="number" x-model="amountTendered" step="0.01" class="block w-full pl-8 pr-3 py-3 text-lg border-2 border-surface-200 rounded-xl focus:ring-primary-500 focus:border-primary-500" placeholder="0.00">
                        </div>
                    </div>

                    <!-- Quick Cash Amounts -->
                    <div class="grid grid-cols-4 gap-2 mb-6">
                        <button @click="amountTendered = total" class="py-2 bg-surface-100 hover:bg-surface-200 text-surface-700 rounded-lg font-medium text-sm transition-colors">Exact</button>
                        <button @click="addAmountTendered(5)" class="py-2 bg-surface-100 hover:bg-surface-200 text-surface-700 rounded-lg font-medium text-sm transition-colors">+$5</button>
                        <button @click="addAmountTendered(10)" class="py-2 bg-surface-100 hover:bg-surface-200 text-surface-700 rounded-lg font-medium text-sm transition-colors">+$10</button>
                        <button @click="addAmountTendered(20)" class="py-2 bg-surface-100 hover:bg-surface-200 text-surface-700 rounded-lg font-medium text-sm transition-colors">+$20</button>
                    </div>

                    <div class="bg-surface-50 rounded-xl p-4 mb-6 border border-surface-200 flex justify-between items-center">
                        <span class="text-surface-600 font-medium">Change Due:</span>
                        <span class="text-xl font-bold" :class="changeDue >= 0 ? 'text-green-600' : 'text-red-600'" x-text="formatCurrency(changeDue)"></span>
                    </div>

                    <button @click="completeSale()" :disabled="changeDue < 0 || isProcessing" class="w-full py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2">
                        <span x-show="!isProcessing">Complete Sale</span>
                        <span x-show="isProcessing">Processing...</span>
                        <svg x-show="isProcessing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Print Receipt iframe placeholder (invisible) -->
        <iframe id="receipt-printer" style="display:none;"></iframe>
    </div>

    <!-- Alpine.js Application Logic -->
    <script>
        function posSystem() {
            return {
                searchQuery: '',
                selectedCategory: null,
                cart: [],
                customer: {
                    name: '',
                    phone: ''
                },
                paymentMethod: 'cash',
                isPaymentModalOpen: false,
                amountTendered: '',
                isProcessing: false,
                
                // Computed properties
                get subtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                },
                get tax() {
                    return 0; // Tax logic can go here
                },
                get total() {
                    return this.subtotal + this.tax;
                },
                get changeDue() {
                    return (parseFloat(this.amountTendered) || 0) - this.total;
                },

                // Search Filter
                matchesSearch(name, categoryId) {
                    const matchesCategory = this.selectedCategory === null || this.selectedCategory === categoryId;
                    const matchesText = name.toLowerCase().includes(this.searchQuery.toLowerCase());
                    return matchesCategory && matchesText;
                },

                // Format Currency
                formatCurrency(value) {
                    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
                },

                // Cart Operations
                addToCart(id, name, price, image) {
                    const existingItem = this.cart.find(item => item.id === id);
                    if (existingItem) {
                        existingItem.quantity += 1;
                    } else {
                        this.cart.push({ id, name, price, image, quantity: 1 });
                    }
                    this.playBeep();
                },
                updateQuantity(id, newQuantity) {
                    if (newQuantity < 1) {
                        this.removeFromCart(id);
                        return;
                    }
                    const item = this.cart.find(item => item.id === id);
                    if (item) item.quantity = newQuantity;
                },
                removeFromCart(id) {
                    this.cart = this.cart.filter(item => item.id !== id);
                },
                clearCart() {
                    if(confirm("Are you sure you want to clear the current sale?")) {
                        this.cart = [];
                        this.customer = { name: '', phone: '' };
                        this.amountTendered = '';
                    }
                },

                // Payment Operations
                openPaymentModal() {
                    this.isPaymentModalOpen = true;
                    // Auto-focus input after transition
                    setTimeout(() => {
                        const input = document.querySelector('input[type="number"]');
                        if(input) { input.focus(); input.select(); }
                    }, 100);
                },
                addAmountTendered(amount) {
                    const current = parseFloat(this.amountTendered) || 0;
                    this.amountTendered = (current + amount).toFixed(2);
                },
                processCardPayment() {
                    // In real life, trigger terminal interface here.
                    alert("Card processing simulation. Press OK to complete.");
                    this.paymentMethod = 'card';
                    this.completeSale();
                },

                async completeSale() {
                    if (this.cart.length === 0 || this.isProcessing) return;
                    
                    this.isProcessing = true;

                    try {
                        const response = await fetch("{{ route('admin.pos.checkout') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                customer_name: this.customer.name,
                                customer_phone: this.customer.phone,
                                payment_method: this.paymentMethod,
                                amount_paid: this.paymentMethod === 'cash' ? parseFloat(this.amountTendered) : this.total,
                                items: this.cart.map(item => ({
                                    id: item.id,
                                    quantity: item.quantity,
                                    price: item.price
                                }))
                            })
                        });

                        const data = await response.json();

                        if (response.ok) {
                            // Success!
                            this.printReceipt(data.order_id);
                            
                            // Reset
                            this.cart = [];
                            this.customer = { name: '', phone: '' };
                            this.amountTendered = '';
                            this.isPaymentModalOpen = false;
                            
                            alert('Sale completed successfully! Invoice: ' + data.order_id);
                        } else {
                            alert(data.message || 'An error occurred during checkout.');
                        }
                    } catch (error) {
                        alert('A network error occurred.');
                        console.error(error);
                    } finally {
                        this.isProcessing = false;
                    }
                },

                // Fake print logic
                printReceipt(orderId) {
                    console.log("Printing receipt for " + orderId);
                    // Generate HTML receipt, append to iframe, call iframe.contentWindow.print()
                },

                // Tiny beep using Audio API for UI feedback
                playBeep() {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = ctx.createOscillator();
                    const gainNode = ctx.createGain();
                    osc.connect(gainNode);
                    gainNode.connect(ctx.destination);
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(800, ctx.currentTime);
                    gainNode.gain.setValueAtTime(0.1, ctx.currentTime);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.05);
                }
            }
        }
    </script>
</body>
</html>

<x-filament-panels::page>
    <div id="jewelry-pos-app" class="pos-container">
        <!-- Main Content Area (Flex Row) -->
        <div class="pos-main">

            <!-- Left Side: Product Catalog (Scrollable) -->
            <div class="pos-catalog">
                <!-- Search Section (Standard Component for Stability) -->
                <div class="pos-search-box ">
                    <x-filament::section compact>
                        <div class="search-input-wrapper">
                            <x-filament::icon icon="heroicon-m-magnifying-glass" class="search-icon" />
                            <input type="text" placeholder="Find jewelry or scan barcode..."
                                wire:model.live.debounce.400ms="search" autofocus id="pos-search-input"
                                class="search-field" />
                        </div>
                    </x-filament::section>
                </div>

                <!-- Products Grid -->
                <div class="pos-products-scroll custom-scrollbar ">
                    <div class="pos-grid ">
                        @forelse($this->products as $product)
                            <div style="margin-top: 8px;" wire:click="addToCart({{ $product->id }})" class="pos-card group">
                                <div class="card-image-wrapper">
                                    @if($product->photo)
                                        <img src="{{ Storage::url($product->photo) }}" class="card-image">
                                    @else
                                        <div class="card-image-placeholder">
                                            <x-filament::icon icon="heroicon-o-sparkles" class="placeholder-icon" />
                                        </div>
                                    @endif
                                    <div class="card-overlay">
                                        <span class="overlay-btn">Select</span>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <h4 class="card-title">{{ $product->name }}</h4>
                                    <div class="card-footer">
                                        <span class="card-price">GHS {{ number_format($product->selling_price, 2) }}</span>
                                        <span class="card-stock {{ $product->quantity_in_stock <= 5 ? 'low-stock' : '' }}">
                                            {{ $product->quantity_in_stock }} UN
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <x-filament::icon icon="heroicon-o-archive-box-x-mark" class="empty-icon" />
                                <p>NO TREASURES FOUND</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Side: Order Sidebar (Sticky/Independent) -->
            <div class="pos-sidebar">
                <div class="sidebar-header">
                    <div>
                        <h3 class="sidebar-title">Current Selection</h3>
                        <p class="sidebar-subtitle">{{ count($cart) }} Items in cart</p>
                    </div>
                    <button wire:click="$set('cart', [])" class="reset-link">Void All</button>
                </div>

                <!-- Cart Items (Independent Scroll) -->
                <div class="sidebar-items custom-scrollbar" id="receipt-list">
                    @forelse($cart as $item)
                        <div class="cart-item">
                            <div class="item-img-box">
                                @if($item['photo'] ?? null)
                                    <img src="{{ Storage::url($item['photo']) }}">
                                @else
                                    <x-filament::icon icon="heroicon-o-sparkles" />
                                @endif
                            </div>
                            <div class="item-details">
                                <h5 class="item-name">{{ $item['name'] }}</h5>
                                <div class="item-meta">
                                    <span class="item-price">GHS {{ number_format($item['price'], 2) }}</span>
                                    <span class="item-qty">× {{ $item['quantity'] }}</span>
                                </div>
                            </div>
                            <div class="item-controls">
                                <button wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})"
                                    class="qty-btn">+</button>
                                <button wire:click="updateQuantity({{ $item['id'] }}, {{ max(1, $item['quantity'] - 1) }})"
                                    class="qty-btn">-</button>
                            </div>
                            <div class="item-subtotal">
                                GHS {{ number_format($item['subtotal'], 2) }}
                            </div>
                        </div>
                    @empty
                        <div class="cart-empty">
                            <x-filament::icon icon="heroicon-o-shopping-bag" />
                            <p>Cart is Empty</p>
                        </div>
                    @endforelse
                </div>

                <!-- Summary & Checkout (Fixed at bottom of sidebar) -->
                <div class="sidebar-footer">
                    <div class="footer-inputs">
                        <select wire:model.live="customerId" class="pos-select">
                            <option value="">Walking Guest</option>
                            @foreach($this->customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        <div class="footer-grid">
                            <input type="number" wire:model.live="discount" class="pos-input" placeholder="DISCOUNT" />
                            <input type="number" wire:model.live="amountPaid" class="pos-input" placeholder="PAID" />
                        </div>
                    </div>

                    <div class="footer-summary">
                        <div class="summary-line">
                            <span>Subtotal</span>
                            <span>GHS {{ number_format($this->subtotal, 2) }}</span>
                        </div>
                        <div class="total-box">
                            <span class="total-label">Payable Total</span>
                            <div class="total-value">
                                <span class="currency">GHS</span>
                                <span class="amount">{{ number_format($this->grandTotal, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <button wire:click="checkout" wire:loading.attr="disabled" class="checkout-btn">
                        <span wire:loading.remove>CONFIRM TRANSACTION</span>
                        <span wire:loading class="loading-text">SECURING VAULT...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('print-receipt', ({ saleId }) => {
            const printWindow = window.open(`/admin/sales/receipt/${saleId}`, '_blank', 'width=400,height=600');
            if (printWindow) printWindow.focus();
        });

        document.addEventListener('livewire:initialized', () => {
            const searchInput = document.getElementById('pos-search-input');

            Livewire.on('added-to-cart', () => {
                if (searchInput) searchInput.focus();
                const list = document.getElementById('receipt-list');
                if (list) list.scrollTo({ top: list.scrollHeight, behavior: 'smooth' });
            });

            Livewire.on('sale-completed', () => { if (searchInput) searchInput.focus(); });

            setTimeout(() => { if (searchInput) searchInput.focus(); }, 300);
        });
    </script>
    @endscript

    <style>
        :root {
            --pos-amber: #f59e0b;
            --pos-dark: #111827;
            --pos-border: rgba(0, 0, 0, 0.05);
            --pos-bg: #fdfdfd;
        }

        .dark :root {
            --pos-border: rgba(255, 255, 255, 0.05);
            --pos-bg: #09090b;
        }

        .pos-container {
            height: calc(100vh - 160px);
            margin: -20px;
            overflow: hidden;
            font-family: 'Inter', system-ui, sans-serif;
        }

        .pos-main {
            display: flex;
            height: 100%;
            gap: 20px;
            padding: 20px;
        }

        /* Catalog Area */
        .pos-catalog {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
            min-width: 0;
        }

        .pos-search-box {
            flex-shrink: 0;
        }

        .search-input-wrapper {
            position: relative;
            display: flex;
            items: center;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: #9ca3af;
        }

        .search-field {
            width: 100%;
            padding: 12px 12px 12px 45px;
            background: rgba(0, 0, 0, 0.02);
            border: 1px solid var(--pos-border);
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            outline: none;
            transition: all 0.2s;
        }

        .dark .search-field {
            background: rgba(255, 255, 255, 0.02);
        }

        .search-field:focus {
            border-color: var(--pos-amber);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .pos-products-scroll {
            flex: 1;
            overflow-y: auto;
            padding-right: 10px;
        }

        .pos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            padding-bottom: 24px;
        }

        /* Card Styles */
        .pos-card {
            background: white;
            border: 1px solid var(--pos-border);
            border-radius: 20px;
            padding: 12px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .dark .pos-card {
            background: #18181b;
        }

        .pos-card:hover {
            border-color: var(--pos-amber);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .card-image-wrapper {
            aspect-ratio: 1;
            background: #f9fafb;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            margin-bottom: 12px;
        }

        .dark .card-image-wrapper {
            background: #09090b;
        }

        .card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .pos-card:hover .card-image {
            transform: scale(1.1);
        }

        .card-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            items-center: center;
            justify-content: center;
        }

        .placeholder-icon {
            width: 40px;
            height: 40px;
            color: #e5e7eb;
        }

        .dark .placeholder-icon {
            color: #27272a;
        }

        .card-overlay {
            position: absolute;
            inset: 0;
            background: rgba(245, 158, 11, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s;
        }

        .pos-card:hover .card-overlay {
            opacity: 1;
        }

        .overlay-btn {
            background: white;
            color: var(--pos-amber);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .card-title {
            font-size: 13px;
            font-weight: 700;
            color: #374151;
            line-height: 1.3;
            height: 34px;
            overflow: hidden;
        }

        .dark .card-title {
            color: #f4f4f5;
        }

        .card-footer {
            margin-top: auto;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .card-price {
            font-weight: 800;
            color: var(--pos-amber);
            font-size: 14px;
        }

        .card-stock {
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
        }

        .low-stock {
            color: #ef4444;
        }

        /* Sidebar Styles */
        .pos-sidebar {
            width: 400px;
            background: white;
            border: 1px solid var(--pos-border);
            border-radius: 24px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .dark .pos-sidebar {
            background: #18181b;
        }

        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid var(--pos-border);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .sidebar-title {
            font-size: 18px;
            font-weight: 900;
            font-style: italic;
            color: var(--pos-dark);
        }

        .dark .sidebar-title {
            color: white;
        }

        .sidebar-subtitle {
            font-size: 11px;
            font-weight: 700;
            color: var(--pos-amber);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .reset-link {
            font-size: 10px;
            font-weight: 700;
            color: #ef4444;
            text-transform: uppercase;
            border: none;
            background: none;
            cursor: pointer;
        }

        .sidebar-items {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .item-img-box {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #f3f4f6;
            overflow: hidden;
            flex-shrink: 0;
            display: flex;
            items-center: center;
            justify-content: center;
            color: #e5e7eb;
        }

        .dark .item-img-box {
            background: #27272a;
            color: #3f3f46;
        }

        .item-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-details {
            flex: 1;
            min-width: 0;
        }

        .item-name {
            font-size: 12px;
            font-weight: 700;
            color: #374151;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .item-name {
            color: white;
        }

        .item-meta {
            font-size: 10px;
            font-weight: 600;
            color: #9ca3af;
            margin-top: 2px;
        }

        .item-price {
            color: var(--pos-amber);
            font-weight: 800;
        }

        .item-controls {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .qty-btn {
            width: 22px;
            height: 22px;
            background: #f3f4f6;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .dark .qty-btn {
            background: #27272a;
            color: white;
        }

        .qty-btn:hover {
            background: var(--pos-amber);
            color: white;
        }

        .item-subtotal {
            font-size: 12px;
            font-weight: 800;
            color: #111827;
            min-width: 70px;
            text-align: right;
        }

        .dark .item-subtotal {
            color: white;
        }

        .cart-empty {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0.2;
        }

        .cart-empty p {
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 10px;
        }

        .sidebar-footer {
            padding: 24px;
            background: #f9fafb;
            border-top: 1px solid var(--pos-border);
        }

        .dark .sidebar-footer {
            background: #09090b;
        }

        .footer-inputs {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }

        .pos-select,
        .pos-input {
            width: 100%;
            padding: 10px 15px;
            border-radius: 12px;
            border: 1px solid var(--pos-border);
            font-size: 12px;
            font-weight: 700;
            background: white;
            outline: none;
        }

        .dark .pos-select,
        .dark .pos-input {
            background: #18181b;
            color: white;
        }

        .pos-input:focus {
            border-color: var(--pos-amber);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .footer-summary {
            margin-bottom: 20px;
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .total-box {
            background: var(--pos-amber);
            padding: 20px;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.2);
            position: relative;
            overflow: hidden;
        }

        .total-box::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, transparent 100%);
        }

        .total-label {
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            opacity: 0.8;
            margin-bottom: 5px;
        }

        .total-value {
            display: flex;
            align-items: baseline;
            gap: 5px;
            position: relative;
            z-index: 1;
        }

        .currency {
            font-size: 14px;
            font-weight: 700;
            opacity: 0.8;
        }

        .amount {
            font-size: 32px;
            font-weight: 900;
            letter-spacing: -1px;
        }

        .checkout-btn {
            width: 100%;
            padding: 18px;
            background: var(--pos-dark);
            color: white;
            border: none;
            border-radius: 18px;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .dark .checkout-btn {
            background: white;
            color: black;
        }

        .checkout-btn:hover {
            background: #000;
            transform: translateY(-1px);
        }

        .checkout-btn:active {
            transform: translateY(0);
        }

        .checkout-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Helpers */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
</x-filament-panels::page>
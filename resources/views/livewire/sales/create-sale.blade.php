<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">New Sale</h1>
            <div class="text-sm opacity-60">Create a new sales transaction</div>
        </div>
        <a href="{{ route('sales.index') }}" class="btn btn-ghost">Cancel</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form Area (Products & Cart) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Product Search & Results -->
            <div class="card bg-base-100 shadow-sm border border-base-200 overflow-visible z-20">
                <div class="card-body p-5 gap-4">
                    <div class="form-control">
                        <label class="label font-bold text-base">Add Product to Cart</label>
                        <div class="relative">
                            <label
                                class="input input-bordered flex items-center gap-2 shadow-sm focus-within:ring-2 ring-primary/20">
                                <span class="icon-[tabler--search] opacity-50"></span>
                                <input type="text" wire:model.live.debounce.300ms="search" class="grow"
                                    placeholder="Start typing product name or SKU..." autofocus />
                                <span wire:loading wire:target="search"
                                    class="loading loading-spinner loading-xs text-primary"></span>
                            </label>

                            <!-- Dropdown Results -->
                            @if(!empty($search) && !$products->isEmpty())
                                <ul
                                    class="absolute top-full left-0 right-0 mt-2 bg-base-100 border border-base-200 rounded-xl shadow-xl max-h-72 overflow-y-auto z-50 divide-y divide-base-200">
                                    @foreach($products as $product)
                                        <li>
                                            <button wire:click="addToCart({{ $product->id }})"
                                                class="w-full text-left p-3 hover:bg-base-200 transition-colors flex justify-between items-center group">
                                                <div>
                                                    <div
                                                        class="font-bold text-base md:text-lg group-hover:text-primary transition-colors">
                                                        {{ $product->name }}
                                                    </div>
                                                    <div class="text-xs opacity-60 font-mono flex items-center gap-1">
                                                        <span class="icon-[tabler--barcode] size-3"></span> {{ $product->sku }}
                                                    </div>
                                                </div>
                                                <div class="flex flex-col items-end gap-1">
                                                    <span
                                                        class="font-bold text-primary text-lg">₵{{ number_format($product->selling_price, 2) }}</span>
                                                    <span
                                                        class="badge badge-sm {{ $product->quantity_in_stock < 5 ? 'badge-warning' : 'badge-ghost' }}">
                                                        {{ $product->quantity_in_stock }} in stock
                                                    </span>
                                                </div>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            @elseif(!empty($search))
                                <div
                                    class="absolute top-full left-0 right-0 mt-2 bg-base-100 border border-base-200 rounded-xl shadow-xl p-4 text-center z-50">
                                    <div class="flex flex-col items-center opacity-60">
                                        <span class="icon-[tabler--package-off] size-8 mb-1"></span>
                                        <span class="text-sm">No products found for "{{ $search }}"</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Table -->
            <div class="card bg-base-100 shadow-sm border border-base-200 min-h-[400px]">
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead class="bg-base-200/50 text-base-content/70">
                                <tr>
                                    <th class="w-1/2 ps-6">Product Details</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-right">Total</th>
                                    <th class="w-16 text-center pe-6"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-base-200">
                                @forelse($cart as $item)
                                    <tr class="hover:bg-base-200/30 transition-colors">
                                        <td class="ps-6">
                                            <div class="font-bold text-base">{{ $item['name'] }}</div>
                                            <div class="text-xs opacity-60 font-mono">{{ $item['sku'] }}</div>
                                        </td>
                                        <td class="text-center font-mono">₵{{ number_format($item['price'], 2) }}</td>
                                        <td>
                                            <div class="flex items-center justify-center gap-2">
                                                <button
                                                    wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})"
                                                    class="btn btn-xs btn-circle btn-ghost border border-base-300 hover:bg-base-300 hover:border-base-400">
                                                    <span class="icon-[tabler--minus] size-3"></span>
                                                </button>
                                                <span
                                                    class="font-mono w-8 text-center font-bold">{{ $item['quantity'] }}</span>
                                                <button
                                                    wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})"
                                                    class="btn btn-xs btn-circle btn-ghost border border-base-300 hover:bg-base-300 hover:border-base-400">
                                                    <span class="icon-[tabler--plus] size-3"></span>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-right font-bold text-base font-mono">
                                            ₵{{ number_format($item['price'] * $item['quantity'], 2) }}
                                        </td>
                                        <td class="text-center pe-6">
                                            <button wire:click="removeFromCart({{ $item['id'] }})"
                                                class="btn btn-ghost btn-xs btn-square text-error/70 hover:text-error hover:bg-error/10 tooltip tooltip-left"
                                                data-tip="Remove">
                                                <span class="icon-[tabler--trash] size-5"></span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-24">
                                            <div
                                                class="flex flex-col items-center justify-center text-base-content/30 gap-3">
                                                <div class="bg-base-200 p-4 rounded-full">
                                                    <span class="icon-[tabler--shopping-cart] size-12"></span>
                                                </div>
                                                <div class="text-center">
                                                    <p class="font-bold text-lg">Your cart is empty</p>
                                                    <p class="text-sm">Search for products above to begin sale</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Customer & Checkout -->
        <div class="space-y-6">

            <!-- Customer Section -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-5 space-y-4">
                    <h3 class="font-bold text-lg flex items-center gap-2 border-b border-base-200 pb-3">
                        <span class="icon-[tabler--user] text-primary"></span> Customer
                    </h3>

                    @if($selectedCustomer)
                        <div
                            class="bg-base-200/50 rounded-xl p-4 border border-base-200 relative group transition-all hover:bg-base-200">
                            <button wire:click="clearCustomerSelection"
                                class="absolute top-2 right-2 btn btn-xs btn-circle btn-ghost text-error/60 hover:text-error hover:bg-error/10"
                                title="Remove Customer">
                                <span class="icon-[tabler--x]"></span>
                            </button>
                            <div class="font-bold text-lg mb-1">{{ $selectedCustomer->name }}</div>
                            <div class="text-sm opacity-70 flex flex-col gap-1">
                                @if($selectedCustomer->phone)
                                    <span class="flex items-center gap-2"><span class="icon-[tabler--phone] size-4"></span>
                                        {{ $selectedCustomer->phone }}</span>
                                @endif
                                @if($selectedCustomer->address)
                                    <span class="flex items-center gap-2"><span class="icon-[tabler--map-pin] size-4"></span>
                                        {{ $selectedCustomer->address }}</span>
                                @endif
                            </div>
                            @if($selectedCustomer->is_credit_customer)
                                <div class="mt-3">
                                    <span class="badge badge-info badge-sm gap-1">
                                        <span class="icon-[tabler--check] size-3"></span> Credit Approved
                                    </span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="space-y-3">
                            <div class="relative dropdown w-full">
                                <label class="input input-bordered w-full flex items-center gap-2">
                                    <span class="icon-[tabler--search] opacity-50"></span>
                                    <input type="text" wire:model.live.debounce.300ms="customerSearch" class="grow"
                                        placeholder="Search existing customer..." />
                                </label>

                                @if(!empty($searchedCustomers) && $searchedCustomers->count() > 0)
                                    <ul
                                        class="dropdown-content mt-1 menu bg-base-100 rounded-box z-[50] w-full shadow-lg border border-base-200">
                                        @foreach($searchedCustomers as $cust)
                                            <li>
                                                <button wire:click="selectCustomer({{ $cust->id }})"
                                                    class="flex flex-col items-start gap-0 py-2">
                                                    <span class="font-bold">{{ $cust->name }}</span>
                                                    <span class="text-xs opacity-60">{{ $cust->phone }}</span>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <div class="text-center text-xs opacity-50 font-medium my-2">- OR -</div>

                            <button wire:click="openCustomerModal" class="btn btn-outline btn-primary w-full gap-2">
                                <span class="icon-[tabler--user-plus]"></span> Create New Customer
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Section -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-5 space-y-6">
                    <h3 class="font-bold text-lg flex items-center gap-2 border-b border-base-200 pb-3">
                        <span class="icon-[tabler--credit-card] text-primary"></span> Payment Details
                    </h3>

                    <div
                        class="flex flex-col gap-1 bg-primary/5 p-6 rounded-2xl border border-primary/10 text-center relative overflow-hidden group">
                        <div
                            class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity pointer-events-none">
                            <span class="icon-[tabler--receipt] size-24 transform rotate-12 -mr-6 -mt-6"></span>
                        </div>
                        <span class="text-xs uppercase tracking-widest font-bold text-primary/60">Total Payable</span>
                        <div
                            class="text-4xl lg:text-5xl font-black text-primary tracking-tight flex items-start justify-center gap-1">
                            <span class="text-xl mt-1 opacity-60 font-sans">₵</span>
                            <span>{{ number_format($this->total, 2) }}</span>
                        </div>
                    </div>

                    <!-- Payment Status Toggle Removed -->

                    <div class="grid grid-cols-1 gap-4 animate-fade-in-down">
                        <div class="form-control">
                            <label class="label font-bold text-sm text-base-content/70">Payment Method</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button type="button" wire:click="$set('payment_method', 'cash')"
                                    class="btn h-auto py-3 flex-col gap-1 border-2 {{ $payment_method === 'cash' ? 'border-primary bg-primary/10 text-primary' : 'bg-base-100 border-base-200 text-base-content/60 hover:border-primary/50 hover:bg-base-100' }}">
                                    <span class="icon-[tabler--cash] size-6"></span>
                                    <span class="text-xs font-bold">Cash</span>
                                </button>

                                <button type="button" wire:click="$set('payment_method', 'mobile_money')"
                                    class="btn h-auto py-3 flex-col gap-1 border-2 {{ $payment_method === 'mobile_money' ? 'border-primary bg-primary/10 text-primary' : 'bg-base-100 border-base-200 text-base-content/60 hover:border-primary/50 hover:bg-base-100' }}">
                                    <span class="icon-[tabler--device-mobile] size-6"></span>
                                    <span class="text-xs font-bold">Mobile Money/Bank Transfer</span>
                                </button>
                            </div>
                        </div>
                        <div class="form-control">
                            <label class="label font-bold text-sm text-base-content/70">Amount Paid</label>
                            <div class="join w-full">
                                <label class="input input-bordered flex items-center gap-2 join-item grow">
                                    <span class="text-base-content/50">₵</span>
                                    <input type="number" wire:model.live="payment_amount"
                                        class="grow font-bold font-mono" placeholder="0.00" />
                                </label>
                                <button wire:click="$set('payment_amount', {{ $this->total }})"
                                    class="btn join-item btn-primary btn-outline" title="Click to pay full amount">
                                    Full
                                </button>
                            </div>
                        </div>

                        <!-- Dynamic Payment Status Feedback -->
                        <div class="mt-4" x-data="{ 
                                amount: @entangle('payment_amount'), 
                                total: @entangle('cart') ? {{ $this->total }} : 0 
                             }" x-effect="total = {{ $this->total }}">

                            <template x-if="parseFloat(amount) >= parseFloat(total) && parseFloat(total) > 0">
                                <div class="alert alert-success shadow-sm py-2">
                                    <span class="icon-[tabler--check] size-5"></span>
                                    <div class="text-sm font-bold">Fully Paid</div>
                                </div>
                            </template>

                            <template x-if="parseFloat(amount) > 0 && parseFloat(amount) < parseFloat(total)">
                                <div class="alert alert-warning shadow-sm py-2">
                                    <span class="icon-[tabler--scale] size-5"></span>
                                    <div>
                                        <div class="text-sm font-bold">Partial Payment</div>
                                        <div class="text-xs">Balance: ₵<span
                                                x-text="(parseFloat(total) - parseFloat(amount)).toFixed(2)"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template x-if="parseFloat(amount) == 0 && parseFloat(total) > 0">
                                <div class="alert alert-info shadow-sm py-2">
                                    <span class="icon-[tabler--credit-card-off] size-5"></span>
                                    <div>
                                        <div class="text-sm font-bold">Credit Sale</div>
                                        <div class="text-xs">Full amount added to balance.</div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <button wire:click="save"
                        class="btn btn-primary w-full btn-lg shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all font-bold text-lg"
                        wire:loading.attr="disabled" {{ empty($cart) ? 'disabled' : '' }}>
                        <span class="flex items-center gap-2">
                            <span class="icon-[tabler--check] size-6"></span> Complete Sale
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Polished Customer Modal -->
    @if($showCustomerModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-brand-950/60 backdrop-blur-sm transition-opacity"
            x-data x-trap="true">
            <div
                class="modal-box w-full max-w-lg bg-base-100 shadow-2xl scale-100 transform transition-transform border border-base-200 p-0 overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-base-200 p-4 border-b border-base-300 flex justify-between items-center">
                    <h3 class="font-bold text-lg flex items-center gap-2">
                        <span class="icon-[tabler--user-plus] text-primary"></span>
                        New Customer
                    </h3>
                    <button wire:click="closeCustomerModal" class="btn btn-sm btn-circle btn-ghost">✕</button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4">
                    @if($existingCustomerInstance)
                        <div class="alert alert-warning shadow-lg">
                            <span class="icon-[tabler--alert-circle] size-6 text-warning-content"></span>
                            <div class="flex-1">
                                <h3 class="font-bold text-sm">Customer Already Exists!</h3>
                                <div class="text-xs">Phone number belongs to
                                    <strong>{{ $existingCustomerInstance->name }}</strong>.</div>
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="$set('existingCustomerInstance', null)" class="btn btn-sm btn-ghost">Change
                                    Number</button>
                                <button wire:click="useExistingCustomer" class="btn btn-sm btn-primary">Load
                                    {{ explode(' ', $existingCustomerInstance->name)[0] }}</button>
                            </div>
                        </div>
                    @endif

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Full Name</span></label>
                        <input type="text" wire:model="newCustomerName" class="input input-bordered focus:input-primary"
                            placeholder="e.g. John Doe" autofocus />
                        @error('newCustomerName') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Phone Number</span></label>
                            <input type="text" wire:model="newCustomerPhone"
                                class="input input-bordered focus:input-primary" placeholder="e.g. 050 123 4567" />
                            @error('newCustomerPhone') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold">Credit Allowed?</span></label>
                            <label
                                class="label cursor-pointer justify-start gap-3 bg-base-200 rounded-lg h-[3rem] px-3 border border-transparent transition-all {{ $newCustomerIsCredit ? 'border-primary/50 bg-primary/5 shadow-sm' : 'hover:border-primary/30' }}">
                                <input type="checkbox" wire:model="newCustomerIsCredit" class="checkbox checkbox-primary" />
                                <span class="label-text font-medium {{ $newCustomerIsCredit ? 'text-primary' : '' }}">Yes,
                                    allow credit</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Address / Location</span></label>
                        <input type="text" wire:model="newCustomerAddress" class="input input-bordered focus:input-primary"
                            placeholder="e.g. Accra, East Legon" />
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-base-200/50 p-4 border-t border-base-200 flex justify-end gap-2">
                    <button wire:click="closeCustomerModal" class="btn btn-ghost">Cancel</button>
                    <button wire:click="saveNewCustomer" class="btn btn-primary px-6 shadow-md shadow-primary/20">
                        Create Customer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
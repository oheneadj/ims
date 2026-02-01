<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Record Payment</h1>
        <a href="{{ route('payments.index') }}" class="btn btn-outline">Cancel</a>
    </div>

    <form wire:submit.prevent="save" class="card bg-base-100 shadow-xl">
        <div class="card-body p-8 gap-8">

            {{-- Payment Info Section --}}
            <section>
                <div class="flex items-center gap-2 mb-6">
                    <span class="w-8 h-8 rounded-lg bg-green-500/10 text-green-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                    </span>
                    <h3 class="text-lg font-bold">Payment Details</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Searchable Customer Input -->
                    <div class="form-control relative">
                        <label class="label"><span class="label-text font-medium">Customer</span></label>
                        
                        @if($customer_id)
                             <div class="flex gap-2">
                                <div class="input input-bordered w-full flex items-center bg-base-200">
                                    {{ $customers->firstWhere('id', $customer_id)->name ?? $customerSearch }}
                                </div>
                                <button type="button" wire:click="$set('customer_id', '')" class="btn btn-square btn-outline">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                             </div>
                        @else
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="customerSearch"
                                class="input input-bordered w-full"
                                placeholder="Search customer name..."
                            />
                            @if(strlen($customerSearch) > 1)
                                <ul class="menu bg-base-100 w-full rounded-box shadow-lg border border-base-200 absolute top-full left-0 z-50 mt-1 max-h-60 overflow-y-auto">
                                    @forelse($customers as $c)
                                        @if(stripos($c->name, $customerSearch) !== false)
                                            <li><a wire:click="selectCustomer({{ $c->id }})">{{ $c->name }} <span class="text-xs opacity-50">({{ $c->phone }})</span></a></li>
                                        @endif
                                    @empty
                                        <li class="disabled"><a>No customers found</a></li>
                                    @endforelse
                                </ul>
                            @endif
                        @endif
                        @error('customer_id') <span class="text-error text-sm mt-1">Please select a customer.</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Sale (Optional)</span></label>
                        <select wire:model.live="sale_id" class="select select-bordered w-full" @if(!$customer_id) disabled @endif>
                            <option value="">-- General Payment --</option>
                            @foreach($customerSales as $sale)
                                @php
                                    $balance = $sale->total_amount - $sale->amount_paid;
                                @endphp
                                <option value="{{ $sale->id }}">
                                    Sale #{{ $sale->id }} (Due: ₵{{ number_format($balance, 2) }})
                                </option>
                            @endforeach
                        </select>
                         @if($sale_id)
                            @php
                                $selectedSale = $customerSales->firstWhere('id', $sale_id);
                                $balance = $selectedSale ? ($selectedSale->total_amount - $selectedSale->amount_paid) : 0;
                            @endphp
                            <div class="text-xs text-info mt-1 font-semibold">
                                Remaining Balance: ₵{{ number_format($balance, 2) }}
                            </div>
                        @else
                            <div class="text-xs opacity-60 mt-1">Link to a specific sale to reduce its balance.</div>
                        @endif
                        @error('sale_id') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Amount (₵)</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 opacity-50">₵</span>
                            <input wire:model="amount" type="number" step="0.01"
                                class="input input-bordered w-full pl-7 @error('amount') input-error @enderror"
                                placeholder="0.00" />
                        </div>
                        @error('amount') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Date</span></label>
                        <input wire:model="payment_date" type="date"
                            class="input input-bordered w-full @error('payment_date') input-error @enderror" />
                        @error('payment_date') <span class="text-error text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Payment Method</span></label>
                        <select wire:model="payment_method" class="select select-bordered w-full">
                            @foreach(\App\Enums\PaymentMethod::cases() as $method)
                                <option value="{{ $method->value }}">{{ $method->label() }}</option>
                            @endforeach
                        </select>
                        @error('payment_method') <span class="text-error text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="label"><span class="label-text font-medium">Notes
                                (Optional)</span></label>
                        <textarea wire:model="notes" class="textarea textarea-bordered h-24 text-base"
                            placeholder="e.g. Check number, bank reference..."></textarea>
                        @error('notes') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </section>
        </div>
        <div class="card-actions justify-end p-4 bg-base-200/50 border-t border-base-200">
            <button type="submit" class="btn btn-primary">
                Record Payment
            </button>
        </div>
    </form>
</div>
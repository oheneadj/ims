<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Payment #{{ $payment->id }}</h1>
            <div class="text-sm opacity-60">
                Recorded on {{ $payment->payment_date->format('F j, Y g:i A') }}
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('payments.index') }}" class="btn btn-ghost">Back</a>
            <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-outline" title="Edit">
                <span class="icon-[tabler--pencil] size-5"></span>
            </a>
            <button class="btn btn-outline" onclick="window.print()">Print</button>
            <button class="btn btn-error btn-outline" x-data x-on:click="$dispatch('open-delete-modal')">
                Delete
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Payment Info -->
        <div>
            <div class="card p-6">
                <h3 class="card-title text-sm opacity-70 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-success"></span>
                    Payment Details
                </h3>

                <div class="mt-4 space-y-4">
                    <div class="flex justify-between items-center border-b border-base-200 pb-2">
                        <span class="opacity-70">Amount Paid</span>
                        <span class="text-2xl font-bold text-success">+₵{{ number_format($payment->amount, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="opacity-70">Payment Method</span>
                        <span class="badge badge-lg">{{ $payment->payment_method->label() }}</span>
                    </div>

                    @if($payment->notes)
                        <div class="pt-2">
                            <span class="opacity-70 block mb-1">Notes</span>
                            <div class="bg-base-200 p-3 rounded-lg text-sm italic">
                                "{{ $payment->notes }}"
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Linked Entities -->
        <div class="space-y-6">
            <!-- Customer Card -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="card-title text-sm opacity-70">Customer</h3>
                    @if($payment->customer)
                        <div class="flex items-center gap-4 mt-2">
                            <div class="avatar placeholder">
                                <div class="bg-neutral-focus text-neutral-content rounded-full w-12 h-12">
                                    <span class="text-xl">{{ substr($payment->customer->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('customers.show', $payment->customer->id) }}"
                                    class="font-bold hover:underline link-primary">
                                    {{ $payment->customer->name }}
                                </a>
                                <div class="text-sm opacity-60">{{ $payment->customer->phone }}</div>
                            </div>
                        </div>
                    @else
                        <div class="py-4 text-center opacity-50 italic">
                            Walk-in Customer
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sale Reference Card -->
            @if($payment->sale)
                <div class="card bg-base-100 shadow-sm border border-base-200">
                    <div class="card-body">
                        <div class="flex justify-between items-start">
                            <h3 class="card-title text-sm opacity-70">Linked Sale</h3>
                            <a href="{{ route('sales.show', $payment->sale->id) }}" class="btn btn-xs btn-outline">View
                                Sale</a>
                        </div>

                        <div class="mt-4">
                            <div class="text-lg font-bold">Sale #{{ $payment->sale->id }}</div>
                            <div class="text-sm opacity-60 mb-2">{{ $payment->sale->sale_date->format('M d, Y') }}</div>

                            <div class="flex justify-between text-sm border-t border-base-200 pt-2 mt-2">
                                <span>Total Sale Amount:</span>
                                <span>₵{{ number_format($payment->sale->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm mt-1">
                                <span>Balance Remaining:</span>
                                <span
                                    class="{{ ($payment->sale->total_amount - $payment->sale->amount_paid) > 0 ? 'text-error' : 'text-success' }}">
                                    ₵{{ number_format($payment->sale->total_amount - $payment->sale->amount_paid, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card bg-base-100 shadow-sm border border-base-200">
                    <div class="card-body">
                        <h3 class="card-title text-sm opacity-70">Linked Sale</h3>
                        <div class="py-4 text-center opacity-50 italic">
                            General Payment (No specific sale linked)
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-data="{ open: false }" x-on:open-delete-modal.window="open = true" x-show="open" style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div class="card bg-base-100 w-full max-w-sm shadow-2xl scale-100 transform transition-transform">
            <div class="card-body text-center">
                <div class="flex justify-center mb-4 text-error">
                    <span class="icon-[tabler--alert-circle] size-16"></span>
                </div>
                <h3 class="text-xl font-bold">Delete Payment?</h3>
                <p class="py-4 text-base-content/70">
                    Are you sure you want to delete this payment of <span
                        class="font-bold text-base-content">₵{{ number_format($payment->amount, 2) }}</span>?
                    <br>This will adjust the linked sale balance.
                </p>
                <div class="card-actions justify-center gap-4">
                    <button @click="open = false" class="btn btn-ghost">Cancel</button>
                    <button @click="open = false; $wire.deletePayment()" class="btn btn-error text-white">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Sale #{{ $sale->id }}</h1>
            <div class="text-sm opacity-60">
                {{ $sale->sale_date->format('F j, Y g:i A') }}
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('sales.index') }}" class="btn btn-ghost">Back to Sales</a>
            @if($sale->payment_status !== \App\Enums\PaymentStatus::PAID)
                <a href="{{ route('payments.create', ['sale_id' => $sale->id]) }}" class="btn btn-secondary">Make
                    Payment</a>
            @endif
            <a href="#" class="btn btn-primary" onclick="window.print()">Print Invoice</a>
        </div>
    </div>

    <!-- Status & Customer Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <h3 class="card-title text-sm opacity-70">Payment Status</h3>
                <div class="mt-2">
                    <span
                        class="badge {{ $sale->payment_status === \App\Enums\PaymentStatus::PAID ? 'badge-success' : ($sale->payment_status === \App\Enums\PaymentStatus::PARTIAL ? 'badge-warning' : 'badge-error') }} badge-lg">
                        {{ $sale->payment_status->label() }}
                    </span>
                    <div class="mt-4">
                        <div class="flex justify-between text-sm">
                            <span>Total Amount:</span>
                            <span class="font-bold">₵{{ number_format($sale->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1">
                            <span>Amount Paid:</span>
                            <span
                                class="font-bold text-success">₵{{ number_format($sale->amount_paid ?? $sale->payments->sum('amount'), 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1 border-t pt-2">
                            <span>Balance Due:</span>
                            <span
                                class="font-bold text-error">₵{{ number_format($sale->total_amount - ($sale->amount_paid ?? $sale->payments->sum('amount')), 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <h3 class="card-title text-sm opacity-70">Customer Details</h3>
                @if($sale->customer)
                    <div class="flex items-center gap-4 mt-2">
                        <div class="avatar placeholder">
                            <div class="bg-neutral-focus text-neutral-content rounded-full w-12 h-12">
                                <span class="text-xl">{{ substr($sale->customer->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="font-bold">{{ $sale->customer->name }}</div>
                            <div class="text-sm opacity-60">{{ $sale->customer->email }}</div>
                            <div class="text-sm opacity-60">{{ $sale->customer->phone }}</div>
                        </div>
                    </div>
                @else
                    <div class="py-4 text-center opacity-50">
                        Walk-in Customer
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="bg-base-200">
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item)
                            <tr>
                                <td>
                                    <div class="font-medium">{{ $item->product->name }}</div>
                                    <div class="text-xs opacity-50">{{ $item->product->sku }}</div>
                                </td>
                                <td class="text-center font-mono">{{ $item->quantity }}</td>
                                <td class="text-right font-mono">₵{{ number_format($item->unit_selling_price, 2) }}</td>
                                <td class="text-right font-mono font-bold">
                                    ₵{{ number_format($item->subtotal ?? ($item->quantity * $item->unit_selling_price), 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Payments History -->
    @if($sale->payments->isNotEmpty())
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <h3 class="card-title text-sm opacity-70 mb-4">Payment History</h3>
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td>
                                        <div class="badge badge-ghost badge-sm">
                                            {{ $payment->payment_method->label() ?? 'Cash' }}
                                        </div>
                                    </td>
                                    <td class="font-mono">₵{{ number_format($payment->amount, 2) }}</td>
                                    <td class="text-xs opacity-60">{{ $payment->notes }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">{{ $customer->name }}</h1>
            <div class="text-sm opacity-60">
                Customer since {{ $customer->created_at->format('M Y') }}
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('customers.index') }}" class="btn btn-ghost">Back to List</a>
            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">Edit Profile</a>
        </div>
    </div>

    <!-- Status & Info Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <h3 class="card-title text-sm opacity-70">Financial Status</h3>
                <div class="mt-2">
                    <span class="badge {{ $customer->current_balance > 0 ? 'badge-error' : 'badge-success' }} badge-lg">
                        {{ $customer->current_balance > 0 ? 'Outstanding Balance' : 'Good Standing' }}
                    </span>
                    <div class="mt-4">
                        <div class="flex justify-between text-sm">
                            <span>Total Sales:</span>
                            <span class="font-bold">₵{{ number_format($totalSalesAmount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1">
                            <span>Total Paid:</span>
                            <span class="font-bold text-success">₵{{ number_format($totalPaymentsAmount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1 border-t pt-2">
                            <span>Balance Due:</span>
                            <span
                                class="font-bold text-error">₵{{ number_format($customer->current_balance, 2) }}</span>
                        </div>
                        @if($customer->is_credit_customer)
                            <div class="flex justify-between text-sm mt-1 pt-2 border-t border-dashed">
                                <span class="opacity-70">Credit Limit:</span>
                                <span class="font-mono">₵{{ number_format($customer->credit_limit, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm mt-1">
                                <span class="opacity-70">Available:</span>
                                <span
                                    class="font-mono text-accent">₵{{ number_format($customer->available_credit, 2) }}</span>
                            </div>
                        @else
                            <div class="mt-2 text-xs opacity-50 text-center">Cash Only Customer</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <h3 class="card-title text-sm opacity-70">Contact Details</h3>
                <div class="flex items-center gap-4 mt-2">
                    <div class="avatar placeholder">
                        <div class="bg-neutral-focus text-neutral-content rounded-full w-12 h-12">
                            <span class="text-xl">{{ substr($customer->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="font-bold">{{ $customer->name }}</div>
                        <div class="text-sm opacity-60">{{ $customer->email ?? 'No email' }}</div>
                        <div class="text-sm opacity-60">{{ $customer->phone ?? 'No phone' }}</div>
                    </div>
                </div>
                @if($customer->address)
                    <div class="mt-4 pt-4 border-t text-sm">
                        <span class="opacity-70 block mb-1">Address</span>
                        <div>{{ $customer->address }}</div>
                    </div>
                @endif
                @if($customer->notes)
                    <div class="mt-4 pt-4 border-t text-sm">
                        <span class="opacity-70 block mb-1">Notes</span>
                        <div class="italic opacity-80">{{ $customer->notes }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sales History (Mimicking Items Table) -->
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-0">
            <div class="p-4 border-b border-base-200 flex justify-between items-center">
                <h3 class="font-bold">Sales History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="bg-base-200">
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Paid</th>
                            <th class="text-right">Balance</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr class="hover">
                                <td>
                                    <div class="font-medium">{{ $sale->sale_date->format('M d, Y') }}</div>
                                    <div class="text-xs opacity-50">#{{ $sale->id }}</div>
                                </td>
                                <td>
                                    <span
                                        class="badge badge-sm badge-outline {{ $sale->payment_status->value === 'paid' ? 'badge-success' : ($sale->payment_status->value === 'partial' ? 'badge-warning' : 'badge-error') }}">
                                        {{ $sale->payment_status->label() }}
                                    </span>
                                </td>
                                <td class="text-right font-bold">₵{{ number_format($sale->total_amount, 2) }}</td>
                                <td class="text-right text-success">₵{{ number_format($sale->amount_paid, 2) }}</td>
                                <td class="text-right text-error font-mono">
                                    ₵{{ number_format($sale->total_amount - $sale->amount_paid, 2) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('sales.show', $sale) }}" class="btn btn-xs btn-ghost">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 opacity-50">
                                    No sales history found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($sales->hasPages())
                <div class="p-4 border-t border-base-200">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Payments History -->
    @if($payments->isNotEmpty())
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <h3 class="card-title text-sm opacity-70 mb-4">Recent Payments</h3>
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Sale Ref</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td>
                                        @if($payment->sale_id)
                                            <a href="{{ route('sales.show', $payment->sale_id) }}"
                                                class="link link-hover text-xs">#{{ $payment->sale_id }}</a>
                                        @else
                                            <span class="opacity-50 text-xs">-</span>
                                        @endif
                                    </td>
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
                @if($payments->hasPages())
                    <div class="mt-4 pt-4 border-t border-base-200">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Payments</h1>
            <div class="text-sm opacity-60">Track all payment transactions</div>
        </div>
        <a href="{{ route('payments.create') }}" class="btn btn-primary">Record Payment</a>
    </div>

    <!-- Search & Filters Card -->
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body py-4">
            <div class="flex flex-wrap items-center gap-4">
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Search by customer, Payment ID, or Sale ID..." class="input input-bordered w-1/2" />

                <select wire:model.live="filterMethod" class="select select-bordered w-1/4">
                    <option value="">All Methods</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->value }}">{{ $method->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="bg-base-200">
                        <tr>
                            <th>ID</th>
                            <th wire:click="sortBy('payment_date')" class="cursor-pointer hover:bg-base-300">
                                <div class="flex items-center gap-1">
                                    Date
                                    @if($sortBy === 'payment_date')
                                        <span
                                            class="icon-[tabler--chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}] size-4"></span>
                                    @endif
                                </div>
                            </th>
                            <th>Customer</th>
                            <th wire:click="sortBy('amount')" class="cursor-pointer hover:bg-base-300">
                                <div class="flex items-center gap-1">
                                    Amount
                                    @if($sortBy === 'amount')
                                        <span
                                            class="icon-[tabler--chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}] size-4"></span>
                                    @endif
                                </div>
                            </th>
                            <th>Method</th>
                            <th>Sale Ref</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td class="font-mono text-sm">#{{ $payment->id }}</td>
                                <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td class="font-semibold">
                                    {{ $payment->customer?->name ?? 'Walk-in Customer' }}
                                </td>
                                <td class="font-bold text-success">
                                    +₵{{ number_format($payment->amount, 2) }}
                                </td>
                                <td>
                                    <span class="badge badge-soft badge-info text-xs">
                                        {{ $payment->payment_method->label() }}
                                    </span>
                                </td>
                                <td>
                                    @if($payment->sale_id)
                                        <a href="{{ route('sales.show', $payment->sale_id) }}"
                                            class="link link-primary text-sm">Sale #{{ $payment->sale_id }}</a>
                                    @else
                                        <span class="text-xs opacity-50">General</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-primary btn-sm">
                                        <span class="icon-[tabler--eye] size-4"></span>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 opacity-50">
                                    No payments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($payments->hasPages())
        {{ $payments->links() }}
    @endif
</div>
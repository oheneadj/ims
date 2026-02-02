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
                                    <div class="flex gap-2">
                                        <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-primary btn-sm">
                                            <span class="icon-[tabler--eye] size-4"></span>
                                        </a>
                                        <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-ghost btn-sm">
                                            <span class="icon-[tabler--pencil] size-4"></span>
                                        </a>
                                        <button class="btn btn-error btn-sm" x-data
                                            x-on:click="$dispatch('open-delete-modal', { id: {{ $payment->id }}, amount: '{{ number_format($payment->amount, 2) }}' })">
                                            <span class="icon-[tabler--trash] size-4"></span>
                                        </button>
                                    </div>
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

    {{-- Delete Confirmation Modal --}}
    <div x-data="{ open: false, id: null, amount: '' }"
        x-on:open-delete-modal.window="open = true; id = $event.detail.id; amount = $event.detail.amount" x-show="open"
        style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div class="card bg-base-100 w-full max-w-sm shadow-2xl scale-100 transform transition-transform">
            <div class="card-body text-center">
                <div class="flex justify-center mb-4 text-error">
                    <span class="icon-[tabler--alert-circle] size-16"></span>
                </div>
                <h3 class="text-xl font-bold">Delete Payment?</h3>
                <p class="py-4 text-base-content/70">
                    Are you sure you want to delete the payment of <span class="font-bold text-base-content">₵<span
                            x-text="amount"></span></span>?
                    <br>This will adjust the linked sale balance.
                </p>
                <div class="card-actions justify-center gap-4">
                    <button @click="open = false" class="btn btn-ghost">Cancel</button>
                    <button @click="open = false; $wire.deletePayment(id)" class="btn btn-error text-white">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
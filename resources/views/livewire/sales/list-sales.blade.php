<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Sales History</h1>
            <div class="text-sm opacity-60">View and manage all sales transactions</div>
        </div>
        <a href="{{ route('sales.create') }}" class="btn btn-primary">New Sale (POS)</a>
    </div>

    <!-- Search & Filters Card -->
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body py-4">
            <div class="flex flex-wrap items-center gap-4">
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Search by customer or Sale ID..." class="input input-bordered w-1/2" />

                <select wire:model.live="filterStatus" class="select select-bordered w-1/4">
                    <option value="">All Status</option>
                    @foreach($paymentStatuses as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="bg-base-200">
                        <tr>
                            <th>Sale ID</th>
                            <th wire:click="sortBy('sale_date')" class="cursor-pointer hover:bg-base-300">
                                <div class="flex items-center gap-1">
                                    Date
                                    @if($sortBy === 'sale_date')
                                        <span
                                            class="icon-[tabler--chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}] size-4"></span>
                                    @endif
                                </div>
                            </th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th wire:click="sortBy('total_amount')" class="cursor-pointer hover:bg-base-300">
                                <div class="flex items-center gap-1">
                                    Total
                                    @if($sortBy === 'total_amount')
                                        <span
                                            class="icon-[tabler--chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}] size-4"></span>
                                    @endif
                                </div>
                            </th>
                            <th>Status</th>
                            <th>Paid</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td class="font-mono font-bold text-sm">IDDL-{{ $sale->id }}</td>
                                <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                                <td class="font-semibold">{{ $sale->customer?->name ?? 'Walk-in Customer' }}</td>
                                <td>{{ $sale->items->sum('quantity') }} items</td>
                                <td class="font-bold">₵{{ number_format($sale->total_amount, 2) }}</td>
                                <td>
                                    @php
                                        $badgeClass = match ($sale->payment_status->value) {
                                            'paid' => 'badge-soft badge-success',
                                            'partial' => 'badge-soft badge-warning',
                                            'credit' => 'badge-soft badge-error',
                                            default => 'badge-soft badge-info'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} text-xs">
                                        {{ $sale->payment_status->label() }}
                                    </span>
                                </td>
                                <td
                                    class="{{ $sale->amount_paid >= $sale->total_amount ? 'text-success' : 'text-warning' }}">
                                    ₵{{ number_format($sale->amount_paid, 2) }}
                                </td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-primary btn-sm">
                                            <span class="icon-[tabler--eye] size-4"></span>
                                        </a>

                                        @if($sale->payment_status->value !== 'paid')
                                            <button wire:click="sendReminder({{ $sale->id }})" class="btn btn-warning btn-sm"
                                                title="Send Payment Reminder">
                                                <span class="icon-[tabler--bell-ringing] size-4"></span>
                                            </button>
                                        @endif

                                        <button class="btn btn-error btn-sm" x-data
                                            x-on:click="$dispatch('open-delete-modal', { id: {{ $sale->id }}, name: 'Sale #IDDL-{{ $sale->id }}' })">
                                            <span class="icon-[tabler--trash] size-4"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-10 opacity-50">
                                    No sales found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($sales->hasPages())
        {{ $sales->links() }}
    @endif

    {{-- Delete Confirmation Modal --}}
    <div x-data="{ open: false, id: null, name: '' }"
        x-on:open-delete-modal.window="open = true; id = $event.detail.id; name = $event.detail.name" x-show="open"
        style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div class="card bg-base-100 w-full max-w-sm shadow-2xl scale-100 transform transition-transform">
            <div class="card-body text-center">
                <div class="flex justify-center mb-4 text-error">
                    <span class="icon-[tabler--alert-circle] size-16"></span>
                </div>
                <h3 class="text-xl font-bold">Delete Sale Record?</h3>
                <p class="py-4 text-base-content/70">
                    Are you sure you want to delete <span class="font-bold text-base-content" x-text="name"></span>?
                    <br>This action cannot be undone.
                </p>
                <div class="card-actions justify-center gap-4">
                    <button @click="open = false" class="btn btn-ghost">Cancel</button>
                    <button @click="open = false; $wire.deleteSale(id)" class="btn btn-error text-white">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
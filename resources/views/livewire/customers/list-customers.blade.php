<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Customers</h1>
            <div class="text-sm opacity-60">Manage your customer database</div>
        </div>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
    </div>

    <!-- Search & Filters Card -->
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body py-4">
            <div class="flex flex-wrap items-center gap-4">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search customers..."
                    class="input input-bordered w-1/2" />

                <select wire:model.live="filterCredit" class="select select-bordered w-1/4">
                    <option value="">All Customers</option>
                    <option value="credit">Credit Enabled</option>
                    <option value="cash">Cash Only</option>
                    <option value="balance">With Outstanding Balance</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="bg-base-200">
                        <tr>
                            <th wire:click="sortBy('name')" class="cursor-pointer hover:bg-base-300">
                                <div class="flex items-center gap-1">
                                    Name
                                    @if($sortBy === 'name')
                                        <span
                                            class="icon-[tabler--chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}] size-4"></span>
                                    @endif
                                </div>
                            </th>
                            <th>Phone</th>
                            <th>Credit Status</th>
                            <th wire:click="sortBy('current_balance')" class="cursor-pointer hover:bg-base-300">
                                <div class="flex items-center gap-1">
                                    Balance
                                    @if($sortBy === 'current_balance')
                                        <span
                                            class="icon-[tabler--chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}] size-4"></span>
                                    @endif
                                </div>
                            </th>
                            <th colspan="2" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td class="font-bold">{{ $customer->name }}</td>
                                <td>
                                    @if($customer->phone)
                                        <span class="text-sm">{{ $customer->phone }}</span>
                                    @else
                                        <span class="text-xs opacity-50">No phone</span>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->is_credit_customer)
                                        <span class="badge badge-soft badge-success text-xs">
                                            Credit (₵{{ number_format($customer->credit_limit, 2) }})
                                        </span>
                                    @else
                                        <span class="badge badge-soft badge-error text-xs">Cash Only</span>
                                    @endif
                                </td>
                                <td class="font-mono">
                                    <span class="{{ $customer->current_balance > 0 ? 'text-error' : 'text-success' }}">
                                        ₵{{ number_format($customer->current_balance, 2) }}
                                    </span>
                                </td>
                                <td colspan="2">
                                    <div class="flex gap-4">
                                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-primary btn-sm">
                                            <span class="icon-[tabler--eye] size-4"></span>
                                            View
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-info btn-sm">
                                            <span class="icon-[tabler--pencil] size-4"></span>
                                            Edit
                                        </a>
                                        <button class="btn btn-error btn-sm" x-data
                                            x-on:click="$dispatch('open-delete-modal', { id: {{ $customer->id }}, name: '{{ addslashes($customer->name) }}' })">
                                            <span class="icon-[tabler--trash] size-4"></span>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 opacity-50">
                                    No customers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($customers->hasPages())
        {{ $customers->links() }}
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
                <h3 class="text-xl font-bold">Delete Customer?</h3>
                <p class="py-4 text-base-content/70">
                    Are you sure you want to delete <span class="font-bold text-base-content" x-text="name"></span>?
                    <br>This action cannot be undone.
                </p>
                <div class="card-actions justify-center gap-4">
                    <button @click="open = false" class="btn btn-ghost">Cancel</button>
                    <button @click="open = false; $wire.deleteCustomer(id)" class="btn btn-error text-white">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Expenses</h1>
            <div class="text-sm opacity-60">Track business expenses and outflows</div>
        </div>
        <a href="{{ route('expenses.create') }}" class="btn btn-primary">Record Expense</a>
    </div>

    <!-- Search & Filters Card -->
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body py-4">
            <div class="flex flex-wrap items-center gap-4">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search expenses..."
                    class="input input-bordered w-1/2" />

                <select wire:model.live="filterCategory" class="select select-bordered w-1/4">
                    <option value="">All Categories</option>
                    @foreach($expenseCategories as $category)
                        <option value="{{ $category->value }}">{{ $category->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="bg-base-200">
                        <tr>
                            <th wire:click="sortBy('expense_date')" class="cursor-pointer hover:bg-base-300">
                                <div class="flex items-center gap-1">
                                    Date
                                    @if($sortBy === 'expense_date')
                                        <span
                                            class="icon-[tabler--chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}] size-4"></span>
                                    @endif
                                </div>
                            </th>
                            <th>Category</th>
                            <th>Description</th>
                            <th wire:click="sortBy('amount')" class="cursor-pointer hover:bg-base-300">
                                <div class="flex items-center gap-1">
                                    Amount
                                    @if($sortBy === 'amount')
                                        <span
                                            class="icon-[tabler--chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}] size-4"></span>
                                    @endif
                                </div>
                            </th>
                            <th>Reference</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td class="font-mono text-sm">{{ $expense->expense_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge badge-soft badge-primary text-xs">
                                        {{ $expense->category->label() ?? ucfirst($expense->category->value) }}
                                    </span>
                                </td>
                                <td>{{ $expense->description }}</td>
                                <td class="font-bold text-error">
                                    -₵{{ number_format($expense->amount, 2) }}
                                </td>
                                <td class="font-mono text-xs opacity-70">
                                    {{ $expense->reference_number ?? '-' }}
                                </td>
                                <td>
                                    <div class="flex gap-2">
                                        <button wire:click="viewExpense({{ $expense->id }})" class="btn btn-primary btn-sm">
                                            <span class="icon-[tabler--eye] size-4"></span>
                                        </button>
                                        <button class="btn btn-error btn-sm" x-data
                                            x-on:click="$dispatch('open-delete-modal', { id: {{ $expense->id }}, name: '{{ addslashes($expense->description) }}' })">
                                            <span class="icon-[tabler--trash] size-4"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 opacity-50">
                                    No expenses found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($expenses->hasPages())
        {{ $expenses->links() }}
    @endif

    <!-- Expense Details Modal -->
    @if($selectedExpense)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-opacity"
            wire:transition.opacity>
            <div class="card bg-base-100 shadow-xl w-full max-w-md" wire:click.outside="closeExpenseModal">
                <div class="card-body">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="font-bold text-lg">Expense Details</h3>
                        <button wire:click="closeExpenseModal" class="btn btn-sm btn-circle btn-ghost">✕</button>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between border-b border-base-200 pb-2">
                            <span class="opacity-70">Date</span>
                            <span class="font-mono">{{ $selectedExpense->expense_date->format('M d, Y') }}</span>
                        </div>

                        <div class="flex justify-between border-b border-base-200 pb-2">
                            <span class="opacity-70">Category</span>
                            <span class="badge badge-soft badge-primary">{{ $selectedExpense->category->label() }}</span>
                        </div>

                        <div class="flex justify-between border-b border-base-200 pb-2">
                            <span class="opacity-70">Amount</span>
                            <span
                                class="font-bold text-error text-xl">-₵{{ number_format($selectedExpense->amount, 2) }}</span>
                        </div>

                        <div class="flex justify-between border-b border-base-200 pb-2">
                            <span class="opacity-70">Reference</span>
                            <span class="font-mono">{{ $selectedExpense->reference_number ?? 'N/A' }}</span>
                        </div>

                        <div>
                            <span class="opacity-70 block mb-1">Description</span>
                            <div class="bg-base-200 p-3 rounded-lg text-sm">
                                {{ $selectedExpense->description }}
                            </div>
                        </div>
                    </div>

                    <div class="card-actions justify-end mt-6">
                        <button wire:click="closeExpenseModal" class="btn">Close</button>
                    </div>
                </div>
            </div>
        </div>
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
                <h3 class="text-xl font-bold">Delete Expense?</h3>
                <p class="py-4 text-base-content/70">
                    Are you sure you want to delete <span class="font-bold text-base-content" x-text="name"></span>?
                    <br>This action cannot be undone.
                </p>
                <div class="card-actions justify-center gap-4">
                    <button @click="open = false" class="btn btn-ghost">Cancel</button>
                    <button @click="open = false; $wire.deleteExpense(id)" class="btn btn-error text-white">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
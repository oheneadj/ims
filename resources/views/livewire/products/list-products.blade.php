<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Products</h1>
            <div class="text-sm opacity-60">Manage your product inventory</div>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
    </div>

    <!-- Search & Filters Card -->
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body py-4">
            <div class="flex flex-wrap items-center gap-4">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search products..."
                    class="input input-bordered w-1/3" />

                <select wire:model.live="filterCategory" class="select select-bordered w-1/5">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filterStock" class="select select-bordered w-1/3">
                    <option value="">All Stock</option>
                    <option value="in">In Stock</option>
                    <option value="low">Low Stock (≤5)</option>
                    <option value="out">Out of Stock</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="bg-base-200">
                        <tr>
                            <th>Image</th>
                            <th wire:click="sortBy('name')" class="cursor-pointer hover:bg-base-300">
                                <div class="flex items-center gap-1">
                                    Name
                                    @if($sortBy === 'name')
                                        <span
                                            class="icon-[tabler--chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}] size-4"></span>
                                    @endif
                                </div>
                            </th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th wire:click="sortBy('selling_price')" class="cursor-pointer hover:bg-base-300">
                                <div class="flex items-center gap-1">
                                    Price
                                    @if($sortBy === 'selling_price')
                                        <span
                                            class="icon-[tabler--chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}] size-4"></span>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('quantity_in_stock')" class="cursor-pointer hover:bg-base-300">
                                <div class="flex items-center gap-1">
                                    Stock
                                    @if($sortBy === 'quantity_in_stock')
                                        <span
                                            class="icon-[tabler--chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}] size-4"></span>
                                    @endif
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <div class="avatar">
                                        <div class="w-12 h-12 mask mask-squircle">
                                            @if($product->photo)
                                                <img src="{{ Storage::url($product->photo) }}" alt="{{ $product->name }}" />
                                            @else
                                                <div
                                                    class="bg-primary text-primary-content w-full h-full flex items-center justify-center font-bold text-xl">
                                                    {{ substr($product->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-bold">{{ $product->name }}</div>
                                    <div class="text-xs opacity-50">{{ $product->material?->label() }}</div>
                                </td>
                                <td class="font-mono text-sm">{{ $product->sku ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-soft badge-primary text-xs">
                                        {{ $product->category?->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="font-mono">₵{{ number_format($product->selling_price, 2) }}</td>
                                <td>
                                    @if($product->quantity_in_stock <= 5)
                                        <span
                                            class="badge badge-soft badge-error text-xs">{{ $product->quantity_in_stock }}</span>
                                    @else
                                        <span
                                            class="badge badge-soft badge-success text-xs">{{ $product->quantity_in_stock }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex gap-1">
                                        <button wire:click="openRestockModal({{ $product->id }})"
                                            class="btn btn-success btn-sm">
                                            <span class="icon-[tabler--package] size-4"></span>
                                            Restock
                                        </button>
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-info btn-sm">
                                            <span class="icon-[tabler--pencil] size-4"></span>
                                            Edit
                                        </a>
                                        <button class="btn btn-error btn-sm" x-data
                                            x-on:click="$dispatch('open-delete-modal', { id: {{ $product->id }}, name: '{{ addslashes($product->name) }}' })">
                                            <span class="icon-[tabler--trash] size-4"></span>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 opacity-50">
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    {{ $products->links() }}

    {{-- Restock Modal --}}
    @if($showRestockModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
            <div class="card bg-base-100 w-full max-w-md shadow-2xl scale-100 transform transition-transform">
                <div class="card-body">
                    <h3 class="card-title text-lg font-bold mb-4">Restock Product</h3>

                    <form wire:submit="saveRestock">
                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-medium">Quantity to Add</span>
                            </label>
                            <input wire:model="restockQuantity" type="number" min="1"
                                class="input input-bordered w-full @error('restockQuantity') input-error @enderror" />
                            @error('restockQuantity') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control w-full mb-6">
                            <label class="label">
                                <span class="label-text font-medium">Notes (Optional)</span>
                            </label>
                            <textarea wire:model="restockNotes" class="textarea textarea-bordered h-24"
                                placeholder="Reason for restock..."></textarea>
                            @error('restockNotes') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="card-actions justify-end gap-2">
                            <button type="button" wire:click="closeRestockModal" class="btn btn-ghost">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Stock</button>
                        </div>
                    </form>
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
                <h3 class="text-xl font-bold">Delete Product?</h3>
                <p class="py-4 text-base-content/70">
                    Are you sure you want to delete <span class="font-bold text-base-content" x-text="name"></span>?
                    <br>This action cannot be undone.
                </p>
                <div class="card-actions justify-center gap-4">
                    <button @click="open = false" class="btn btn-ghost">Cancel</button>
                    <button @click="open = false; $wire.deleteProduct(id)" class="btn btn-error text-white">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Edit Product: {{ $product->name }}</h1>
        <a href="{{ route('products.index') }}" class="btn btn-outline">Cancel</a>
    </div>

    <form wire:submit="save" class="card bg-base-100 shadow-xl">
        <div class="card-body">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Name --}}
                <div class="form-control">
                    <label class="label"><span class="label-text">Product Name</span></label>
                    <input wire:model="name" type="text"
                        class="input input-bordered @error('name') input-error @enderror" />
                    @error('name') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- SKU --}}
                <div class="form-control">
                    <label class="label"><span class="label-text">SKU</span></label>
                    <input wire:model="sku" type="text"
                        class="input input-bordered @error('sku') input-error @enderror" />
                    @error('sku') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                {{-- Type Enum --}}
                {{-- Category Select --}}
                <div class="form-control">
                    <label class="label"><span class="label-text">Category</span></label>
                    <select wire:model.live="category_id" class="select select-bordered w-full">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Material Enum --}}
                <div class="form-control">
                    <label class="label"><span class="label-text">Material</span></label>
                    <select wire:model="material" class="select select-bordered w-full">
                        <option value="">None / Mixed</option>
                        @foreach(\App\Enums\ProductMaterial::cases() as $material)
                            <option value="{{ $material->value }}">{{ $material->label() }}</option>
                        @endforeach
                    </select>
                    @error('material') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-control mt-2">
                <label class="label"><span class="label-text">Description</span></label>
                <textarea wire:model="description" class="textarea textarea-bordered h-24"></textarea>
                @error('description') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                {{-- Cost Price --}}
                <div class="form-control">
                    <label class="label"><span class="label-text">Cost Price</span></label>
                    <label class="input-group">
                        <input wire:model="cost_price" type="number" step="0.01"
                            class="input input-bordered w-full @error('cost_price') input-error @enderror" />
                    </label>
                    @error('cost_price') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Selling Price --}}
                <div class="form-control">
                    <label class="label"><span class="label-text">Selling Price</span></label>
                    <label class="input-group">
                        <input wire:model="selling_price" type="number" step="0.01"
                            class="input input-bordered w-full @error('selling_price') input-error @enderror" />
                    </label>
                    @error('selling_price') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Stock --}}
                <div class="form-control">
                    <label class="label"><span class="label-text">Quantity In Stock</span></label>
                    <input wire:model="quantity_in_stock" type="number"
                        class="input input-bordered w-full @error('quantity_in_stock') input-error @enderror" />
                    @error('quantity_in_stock') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-control mt-4">
                <label class="label"><span class="label-text">Product Photo</span></label>
                @if ($existingPhoto)
                    <div class="mb-2">
                        <p class="text-sm text-gray-500 mb-1">Current Photo:</p>
                        <img src="{{ Storage::url($existingPhoto) }}" class="w-16 h-16 object-cover rounded shadow" />
                    </div>
                @endif

                <x-filepond wire:model="photo" />
                @if ($photo)
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 mb-1">New Photo Preview:</p>
                        <img src="{{ $photo->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg shadow" />
                    </div>
                @endif
                @error('photo') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="card-actions justify-end mt-6">
                <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
        </div>
    </form>
</div>
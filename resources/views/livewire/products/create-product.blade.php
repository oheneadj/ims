<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-base-content">Add New Product</h1>
            <p class="text-base-content/60 mt-1">Create a new item in your inventory.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('products.index') }}" class="btn btn-ghost">Cancel</a>
            <button type="button" wire:click="save" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-2">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                        clip-rule="evenodd" />
                </svg>
                Save Product
            </button>
        </div>
    </div>

    <form wire:submit="save" class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-8 gap-8">

            {{-- Basic Info Section --}}
            <section>
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    Basic Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Product Name</span></label>
                        <input wire:model="name" type="text" placeholder="e.g. Diamond Necklace"
                            class="input input-bordered w-full @error('name') input-error @enderror" />
                        @error('name') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">SKU</span></label>
                        <div class="join w-full">
                            <input wire:model="sku" type="text" placeholder="Auto-generated"
                                class="input input-bordered join-item w-full @error('sku') input-error @enderror"
                                readonly />
                            <button type="button" wire:click="generateSku" class="btn btn-primary join-item"
                                title="Regenerate SKU">
                                <span class="icon-[tabler--refresh] size-5"></span>
                            </button>
                        </div>
                        <label class="label"><span class="label-text-alt text-base-content/60">Auto-generated based on
                                Type and Material</span></label>
                        @error('sku') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="label"><span class="label-text font-medium">Description</span></label>
                        <textarea wire:model="description" class="textarea textarea-bordered h-32 text-base"
                            placeholder="Detailed product description..."></textarea>
                        @error('description') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </section>

            <div class="divider my-0"></div>

            {{-- Specifications Section --}}
            <section>
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd"
                                d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    Specifications
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Type</span></label>
                        <select wire:model="type" class="select select-bordered w-full">
                            @foreach(\App\Enums\ProductType::cases() as $type)
                                <option value="{{ $type->value }}">{{ $type->label() }}</option>
                            @endforeach
                        </select>
                        @error('type') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Material</span></label>
                        <select wire:model="material" class="select select-bordered w-full">
                            <option value="">None / Mixed</option>
                            @foreach(\App\Enums\ProductMaterial::cases() as $material)
                                <option value="{{ $material->value }}">{{ $material->label() }}</option>
                            @endforeach
                        </select>
                        @error('material') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </section>

            <div class="divider my-0"></div>

            {{-- Pricing & Stock Section --}}
            <section>
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-accent/10 text-accent flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path
                                d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                    </span>
                    Pricing & Inventory
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Cost Price</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 opacity-50">₵</span>
                            <input wire:model="cost_price" type="number" step="0.01"
                                class="input input-bordered w-full pl-7 @error('cost_price') input-error @enderror"
                                placeholder="0.00" />
                        </div>
                        @error('cost_price') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Selling Price</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 opacity-50">₵</span>
                            <input wire:model="selling_price" type="number" step="0.01"
                                class="input input-bordered w-full pl-7 @error('selling_price') input-error @enderror"
                                placeholder="0.00" />
                        </div>
                        @error('selling_price') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Starting Stock</span></label>
                        <input wire:model="quantity_in_stock" type="number"
                            class="input input-bordered w-full @error('quantity_in_stock') input-error @enderror"
                            placeholder="0" />
                        @error('quantity_in_stock') <span class="text-error text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </section>

            <div class="divider my-0"></div>

            {{-- Media Section --}}
            <section>
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-neutral/10 text-neutral flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd"
                                d="M1 5.25A2.25 2.25 0 013.25 3h13.5A2.25 2.25 0 0119 5.25v9.5A2.25 2.25 0 0116.75 17H3.25A2.25 2.25 0 011 14.75v-9.5zm1.5 5.81v3.69c0 .414.336.75.75.75h13.5a.75.75 0 00.75-.75v-2.69l-2.22-2.219a.75.75 0 00-1.06 0l-1.91 1.909.47.47a.75.75 0 11-1.06 1.06L6 8.06l-3.5 3z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    Product Image
                </h3>
                <div class="form-control">
                    <x-filepond wire:model="photo" />
                    @if ($photo)
                        <div class="mt-4 p-4 border rounded-xl bg-base-50 inline-block">
                            <p class="text-sm font-medium mb-2 opacity-70">Preview</p>
                            <img src="{{ $photo->temporaryUrl() }}" class="w-48 h-48 object-cover rounded-lg shadow-sm" />
                        </div>
                    @endif
                    @error('photo') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </section>
        </div>
    </form>
</div>
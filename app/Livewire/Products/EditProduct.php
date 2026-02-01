<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Enums\ProductType;
use App\Enums\ProductMaterial;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class EditProduct extends Component
{
    use WithFileUploads;

    public Product $product;

    #[Validate('required|string|max:255')]
    public string $name = '';

    // SKU validation is special, handled in save()
    public string $sku = '';

    #[Validate('required')]
    public string $type = '';

    #[Validate('nullable')]
    public string $material = '';

    #[Validate('nullable|string')]
    public string $description = '';

    #[Validate('required|numeric|min:0')]
    public $cost_price = '';

    #[Validate('required|numeric|min:0')]
    public $selling_price = '';

    #[Validate('required|integer|min:0')]
    public $quantity_in_stock = 0;

    #[Validate('nullable|image|max:2048')]
    public $photo; // New photo

    public $existingPhoto;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->type = $product->type->value;
        $this->material = $product->material?->value ?? '';
        $this->description = $product->description ?? '';
        $this->cost_price = $product->cost_price;
        $this->selling_price = $product->selling_price;
        $this->quantity_in_stock = $product->quantity_in_stock;
        $this->existingPhoto = $product->photo;
    }

    public function save()
    {
        $this->validate([
            'sku' => 'required|unique:products,sku,'.$this->product->id,
            'name' => 'required|string|max:255',
            'type' => 'required',
            'material' => 'nullable',
            'description' => 'nullable|string',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity_in_stock' => 'required|integer|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);

        $path = $this->existingPhoto;
        if ($this->photo) {
            $path = $this->photo->store('products', 'public');
            // Delete old photo if exists?
            if ($this->existingPhoto) {
                Storage::disk('public')->delete($this->existingPhoto);
            }
        }

        $this->product->update([
            'name' => $this->name,
            'sku' => $this->sku,
            'type' => $this->type,
            'material' => $this->material ?: null,
            'description' => $this->description,
            'cost_price' => $this->cost_price,
            'selling_price' => $this->selling_price,
            'quantity_in_stock' => $this->quantity_in_stock,
            'photo' => $path,
        ]);

        session()->flash('status', 'Product updated successfully.');

        return $this->redirect(route('products.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.products.edit-product');
    }
}

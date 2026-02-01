<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Enums\ProductType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class CreateProduct extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|unique:products,sku')]
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
    public $photo;

    public function mount()
    {
        $this->type = ProductType::NECKLACE->value;
        $this->generateSku();
    }

    public function updatedType()
    {
        $this->generateSku();
    }

    public function updatedMaterial()
    {
        $this->generateSku();
    }

    public function generateSku()
    {
        $typePrefix = strtoupper(substr($this->type, 0, 3));
        $materialPrefix = $this->material ? strtoupper(substr($this->material, 0, 3)) : 'GEN';
        $uniqueId = strtoupper(substr(uniqid(), -4));

        $this->sku = "{$typePrefix}-{$materialPrefix}-{$uniqueId}";
    }

    public function save()
    {
        $this->validate();

        $path = $this->photo ? $this->photo->store('products', 'public') : null;

        Product::create([
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

        session()->flash('status', 'Product created successfully.');

        return $this->redirect(route('products.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.products.create-product');
    }
}

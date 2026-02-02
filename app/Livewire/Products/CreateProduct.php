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

    #[Validate('required|exists:categories,id')]
    public $category_id = '';

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
        // $this->category_id = ...; // Optional: set default
        $this->generateSku();
    }

    public function updatedCategoryId()
    {
        $this->generateSku();
    }

    public function updatedMaterial()
    {
        $this->generateSku();
    }

    public function generateSku()
    {
        $catPrefix = 'GEN';
        if ($this->category_id) {
            $category = \App\Models\Category::find($this->category_id);
            if ($category) {
                // Use first 3 letters of slug or name
                $catPrefix = strtoupper(substr($category->slug, 0, 3));
            }
        }
        
        $materialPrefix = $this->material ? strtoupper(substr($this->material, 0, 3)) : 'GEN';
        $uniqueId = strtoupper(substr(uniqid(), -4));

        $this->sku = "{$catPrefix}-{$materialPrefix}-{$uniqueId}";
    }

    public function save()
    {
        $this->validate();

        $path = $this->photo ? $this->photo->store('products', 'public') : null;
        
        // Backward compatibility: Determine legacy 'type' from category if possible
        $category = \App\Models\Category::find($this->category_id);
        $legacyType = $category ? \App\Enums\ProductType::tryFrom($category->slug) : null;
        // Fallback to OTHER if no match
        $legacyType = $legacyType ?? \App\Enums\ProductType::OTHER;

        Product::create([
            'name' => $this->name,
            'sku' => $this->sku,
            'category_id' => $this->category_id,
            'type' => $legacyType, // Legacy column
            'material' => $this->material ?: null,
            'description' => $this->description,
            'cost_price' => $this->cost_price,
            'selling_price' => $this->selling_price,
            'quantity_in_stock' => $this->quantity_in_stock,
            'photo' => $path,
        ]);

        notify()->success()->title('Success')->message('Product created successfully.')->send();

        return $this->redirect(route('products.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.products.create-product', [
            'categories' => \App\Models\Category::orderBy('name')->get(),
        ]);
    }
}

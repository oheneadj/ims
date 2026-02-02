<?php

namespace App\Livewire\Products;

use App\Enums\ProductType;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ListProducts extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';
    public string $filterCategory = '';
    public string $filterStock = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    public function updatedFilterStock()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public $selectedProductId;
    public $restockQuantity = 1;
    public $restockNotes = '';
    public $showRestockModal = false;

    public function openRestockModal($productId)
    {
        $this->selectedProductId = $productId;
        $this->restockQuantity = 1;
        $this->restockNotes = '';
        $this->showRestockModal = true;
    }

    public function closeRestockModal()
    {
        $this->showRestockModal = false;
        $this->reset(['selectedProductId', 'restockQuantity', 'restockNotes']);
    }

    public function restock()
    {
        $this->validate([
            'restockQuantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($this->selectedProductId);
        if ($product) {
            $product->increment('quantity_in_stock', $this->restockQuantity);
            notify()->success()->title('Success')->message("Restocked {$product->name} by {$this->restockQuantity}.")->send();
        }

        $this->closeRestockModal();
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        if ($product->saleItems()->exists()) {
            notify()->error()->title('Action Failed')->message('Cannot delete product because it has associated sales history.')->send();
            return;
        }

        $product->delete();
        notify()->success()->title('Success')->message('Product deleted successfully.')->send();

        return redirect()->route('products.index');
    }

    public function render()
    {
        $products = Product::query()
            ->with(['category'])
            ->when($this->search, fn($q) => $q->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('sku', 'like', '%'.$this->search.'%'))
            ->when($this->filterCategory, fn($q) => $q->where('category_id', $this->filterCategory))
            ->when($this->filterStock === 'low', fn($q) => $q->where('quantity_in_stock', '<=', 5))
            ->when($this->filterStock === 'out', fn($q) => $q->where('quantity_in_stock', '=', 0))
            ->when($this->filterStock === 'in', fn($q) => $q->where('quantity_in_stock', '>', 5))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.products.list-products', [
            'products' => $products,
            'categories' => \App\Models\Category::orderBy('name')->get(),
        ]);
    }
}

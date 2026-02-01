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
    public string $filterType = '';
    public string $filterStock = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
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

    public function saveRestock()
    {
        $this->validate([
            'selectedProductId' => 'required|exists:products,id',
            'restockQuantity' => 'required|integer|min:1',
            'restockNotes' => 'nullable|string|max:255',
        ]);

        $product = Product::find($this->selectedProductId);
        
        // Update Stock
        $product->increment('quantity_in_stock', $this->restockQuantity);

        // Record Movement
        \App\Models\StockMovement::create([
            'product_id' => $product->id,
            'type' => 'adjustment',
            'quantity' => $this->restockQuantity,
            'reference' => $this->restockNotes ?: 'Manual Restock',
        ]);

        $this->closeRestockModal();
        session()->flash('status', 'Stock updated successfully.');
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('sku', 'like', '%'.$this->search.'%'))
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->when($this->filterStock === 'low', fn($q) => $q->where('quantity_in_stock', '<=', 5))
            ->when($this->filterStock === 'out', fn($q) => $q->where('quantity_in_stock', '=', 0))
            ->when($this->filterStock === 'in', fn($q) => $q->where('quantity_in_stock', '>', 5))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.products.list-products', [
            'products' => $products,
            'productTypes' => ProductType::cases(),
        ]);
    }
}

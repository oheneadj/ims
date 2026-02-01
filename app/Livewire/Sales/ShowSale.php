<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Attributes\Layout;
use Livewire\Component;


#[Layout('layouts.app')]
class ShowSale extends Component
{
    public Sale $sale;

    public function mount(Sale $sale)
    {
        $this->sale = $sale->load(['customer', 'items.product', 'payments']);
    }

    public function render()
    {
        return view('livewire.sales.show-sale');
    }
}

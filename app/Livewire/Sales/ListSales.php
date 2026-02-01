<?php

namespace App\Livewire\Sales;

use App\Enums\PaymentStatus;
use App\Models\Sale;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ListSales extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'sale_date';
    public string $sortDirection = 'desc';
    public string $filterStatus = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
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

    public function render()
    {
        $sales = Sale::query()
            ->with(['customer', 'items'])
            ->when($this->search, fn($q) => $q->whereHas('customer', fn($c) => $c->where('name', 'like', '%'.$this->search.'%'))
                ->orWhere('id', 'like', '%'.$this->search.'%'))
            ->when($this->filterStatus, fn($q) => $q->where('payment_status', $this->filterStatus))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.sales.list-sales', [
            'sales' => $sales,
            'paymentStatuses' => PaymentStatus::cases(),
        ]);
    }
}

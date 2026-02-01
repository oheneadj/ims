<?php

namespace App\Livewire\Payments;

use App\Enums\PaymentMethod;
use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ListPayments extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'payment_date';
    public string $sortDirection = 'desc';
    public string $filterMethod = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterMethod()
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
        $payments = Payment::query()
            ->with(['customer', 'sale'])
            ->when($this->search, fn($q) => $q->whereHas('customer', fn($c) => $c->where('name', 'like', '%'.$this->search.'%'))
                ->orWhere('id', 'like', '%'.$this->search.'%')
                ->orWhere('sale_id', 'like', '%'.$this->search.'%'))
            ->when($this->filterMethod, fn($q) => $q->where('payment_method', $this->filterMethod))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.payments.list-payments', [
            'payments' => $payments,
            'paymentMethods' => PaymentMethod::cases(),
        ]);
    }
}

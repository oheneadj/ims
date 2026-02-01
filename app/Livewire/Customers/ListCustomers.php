<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ListCustomers extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';
    public string $filterCredit = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCredit()
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
        $customers = Customer::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('phone', 'like', '%'.$this->search.'%')
                ->orWhere('email', 'like', '%'.$this->search.'%'))
            ->when($this->filterCredit === 'credit', fn($q) => $q->where('is_credit_customer', true))
            ->when($this->filterCredit === 'cash', fn($q) => $q->where('is_credit_customer', false))
            ->when($this->filterCredit === 'balance', fn($q) => $q->where('current_balance', '>', 0))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.customers.list-customers', [
            'customers' => $customers
        ]);
    }
}

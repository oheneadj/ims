<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ShowCustomer extends Component
{
    use WithPagination;
    
    public Customer $customer;

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function render()
    {
        return view('livewire.customers.show-customer', [
            'sales' => $this->customer->sales()->orderBy('sale_date', 'desc')->paginate(5, pageName: 'sales_page'),
            'payments' => $this->customer->payments()->orderBy('payment_date', 'desc')->paginate(5, pageName: 'payments_page'),
            'totalSalesCount' => $this->customer->sales()->count(),
            'totalSalesAmount' => $this->customer->sales()->sum('total_amount'),
            'totalPaymentsAmount' => $this->customer->payments()->sum('amount'),
        ]);
    }
}

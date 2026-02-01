<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class EditCustomer extends Component
{
    public Customer $customer;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:20')]
    public string $phone = '';

    #[Validate('nullable|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string')]
    public string $address = '';

    #[Validate('boolean')]
    public bool $is_credit_customer = false;

    #[Validate('required_if:is_credit_customer,true|numeric|min:0')]
    public $credit_limit = 0;

    #[Validate('nullable|string')]
    public string $notes = '';

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
        $this->name = $customer->name;
        $this->phone = $customer->phone ?? '';
        $this->email = $customer->email ?? '';
        $this->address = $customer->address ?? '';
        $this->is_credit_customer = $customer->is_credit_customer;
        $this->credit_limit = $customer->credit_limit;
        $this->notes = $customer->notes ?? '';
    }

    public function save()
    {
        $this->validate();

        $this->customer->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'is_credit_customer' => $this->is_credit_customer,
            'credit_limit' => $this->credit_limit,
            'notes' => $this->notes,
        ]);

        session()->flash('status', 'Customer updated successfully.');

        return $this->redirect(route('customers.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.customers.edit-customer');
    }
}

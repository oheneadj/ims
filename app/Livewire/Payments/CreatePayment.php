<?php

namespace App\Livewire\Payments;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Sale;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class CreatePayment extends Component
{
    #[Validate('required|exists:customers,id')]
    public $customer_id = '';

    #[Validate('nullable|exists:sales,id')]
    public $sale_id = '';

    #[Validate('required|numeric|min:0.01')]
    public $amount = '';

    #[Validate('required')]
    public string $payment_method = '';

    #[Validate('nullable|string')]
    public string $notes = '';

    #[Validate('required|date')]
    public $payment_date = '';

    public $customerSearch = '';

    public function updatedCustomerSearch()
    {
        $this->customer_id = ''; // Reset selected customer if search changes
    }
    
    public function selectCustomer($id)
    {
        $this->customer_id = $id;
        $this->customerSearch = Customer::find($id)->name;
    }

    public function mount()
    {
        $this->payment_method = PaymentMethod::CASH->value;
        $this->payment_date = now()->format('Y-m-d');

        if (request()->has('sale_id')) {
            $this->sale_id = request()->query('sale_id');
            $sale = Sale::find($this->sale_id);
            if ($sale) {
                // Prevent paying for already fully paid sales
                if ($sale->payment_status === PaymentStatus::PAID) {
                    session()->flash('error', 'Sale #' . $sale->id . ' is already fully paid.');
                    return $this->redirect(route('sales.show', $sale->id));
                }

                $this->customer_id = $sale->customer_id;
                $this->customerSearch = $sale->customer->name; // Pre-fill search
                $this->amount = $sale->total_amount - ($sale->amount_paid ?? 0);
            }
        }
    }

    public function save()
    {
        $this->validate();

        // Optional: specific validation logic
        // If sale_id is set, ensure it belongs to customer
        if ($this->sale_id) {
            $sale = Sale::find($this->sale_id);
            if ($sale->customer_id != $this->customer_id) {
                $this->addError('sale_id', 'Selected sale does not belong to this customer.');
                return;
            }
            // Strict: Don't allow paying more than owed on a specific sale.
            $remaining = $sale->total_amount - $sale->amount_paid;
            // Allow a small buffer for floating point comparisons if needed, but strict for now
            if ($this->amount > $remaining) {
                $this->addError('amount', "Amount cannot exceed the remaining balance of ₵" . number_format($remaining, 2));
                return;
            }
        }

        Payment::create([
            'customer_id' => $this->customer_id,
            'sale_id' => $this->sale_id ?: null,
            'amount' => $this->amount,
            'payment_date' => $this->payment_date,
            'payment_method' => $this->payment_method,
            'notes' => $this->notes,
        ]);

        if ($this->sale_id) {
            return $this->redirect(route('sales.show', $this->sale_id), navigate: true);
        }

        return $this->redirect(route('payments.index'), navigate: true);
    }

    public function render()
    {
        $customers = Customer::orderBy('name')->get();
        
        $customerSales = collect();
        $selectedCustomer = null;

        if ($this->customer_id) {
            $selectedCustomer = Customer::find($this->customer_id);
            // Get unpaid or partial sales
            $customerSales = Sale::where('customer_id', $this->customer_id)
                ->whereIn('payment_status', [PaymentStatus::CREDIT, PaymentStatus::PARTIAL])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('livewire.payments.create-payment', [
            'customers' => $customers,
            'customerSales' => $customerSales,
            'selectedCustomer' => $selectedCustomer,
        ]);
    }
}

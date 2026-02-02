<?php

namespace App\Livewire\Payments;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Sale;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EditPayment extends Component
{
    public Payment $payment;

    public bool $isLocked = true;
    public string $passwordConfirmation = '';

    public $payment_date;
    public $amount;
    public $original_amount; // Track original amount for balance adjustment
    public $payment_method;
    public $notes;
    
    // Read-only info
    public $customer_name;
    public $sale_id;

    public function mount(Payment $payment)
    {
        $this->payment = $payment;
        $this->payment_date = $payment->payment_date->format('Y-m-d');
        $this->amount = $payment->amount;
        $this->original_amount = $payment->amount;
        $this->payment_method = $payment->payment_method->value;
        $this->notes = $payment->notes;
        
        $this->customer_name = $payment->customer?->name;
        $this->sale_id = $payment->sale_id;
    }

    public function unlock()
    {
        $this->validate([
            'passwordConfirmation' => 'required',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (\Illuminate\Support\Facades\Hash::check($this->passwordConfirmation, $user->password)) {
            $this->isLocked = false;
        } else {
            $this->addError('passwordConfirmation', 'Incorrect password.');
        }
    }

    public function update()
    {
        if ($this->isLocked) {
            $this->addError('passwordConfirmation', 'Please unlock first.');
            return;
        }

        $this->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required',
            'notes' => 'nullable|string|max:255',
        ]);

        // 1. Update Payment Record - Observer handles all side effects
        $this->payment->update([
            'payment_date' => $this->payment_date,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'notes' => $this->notes,
        ]);

        notify()->success()->title('Success')->message('Payment updated successfully.')->send();

        return $this->redirect(route('payments.show', $this->payment->id), navigate: true);
    }

    public function render()
    {
        return view('livewire.payments.edit-payment', [
            'paymentMethods' => PaymentMethod::cases(),
        ]);
    }
}

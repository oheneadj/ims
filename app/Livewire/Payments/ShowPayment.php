<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ShowPayment extends Component
{
    public Payment $payment;

    public function mount(Payment $payment)
    {
        $this->payment = $payment->load(['customer', 'sale']);
    }

    public function deletePayment()
    {
        $this->payment->delete(); // Observer handles side effects
        
        notify()->success()->title('Success')->message('Payment deleted successfully.')->send();

        return $this->redirect(route('payments.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.payments.show-payment');
    }
}

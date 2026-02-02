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

    public function deleteSale($id)
    {
        $sale = Sale::findOrFail($id);

        // 1. Delete Payments (Reverses customer balance credit)
        foreach ($sale->payments as $payment) {
            $payment->delete();
        }

        // 2. Delete Items (Restores stock)
        foreach ($sale->items as $item) {
            $item->delete();
        }

        // 3. Delete Sale (Reverses cost to customer)
        $sale->delete();
        
        notify()->success()->title('Success')->message('Sale deleted. Stock restored and payments reversed.')->send();

        return redirect()->route('sales.index');
    }

    public function sendReminder($id)
    {
        $sale = Sale::findOrFail($id);

        if ($sale->payment_status->value === 'paid') {
            notify()->info()->title('Info')->message('This sale is already fully paid.')->send();
            return;
        }

        if (!$sale->customer || !$sale->customer->phone) {
            notify()->error()->title('Error')->message('Customer does not have a valid phone number.')->send();
            return;
        }

        // Logic to dispatch SMS job/service would go here
        // e.g. MnotifyService::send($sale->customer->phone, "Reminder: You have an outstanding balance...");

        notify()->success()->title('Success')->message("Payment reminder sent to {$sale->customer->phone}.")->send();
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

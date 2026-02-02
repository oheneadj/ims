<?php

namespace App\Livewire\Expenses;

use App\Enums\ExpenseCategory;
use App\Models\Expense;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class CreateExpense extends Component
{
    use WithFileUploads;

    #[Validate('required|numeric|min:0.01')]
    public $amount = '';

    #[Validate('required')]
    public string $category = '';
    
    #[Validate('required|string|max:255')]
    public string $description = '';

    #[Validate('required|date')]
    public string $expense_date = '';

    #[Validate('nullable|string|max:50')]
    public string $reference_number = '';

    #[Validate('nullable|image|max:10240')] // 10MB Max
    public $receipt;

    public function mount()
    {
        $this->expense_date = now()->format('Y-m-d');
        $this->category = ExpenseCategory::SUPPLIES->value;
    }

    public function save()
    {
        $this->validate();

        Expense::create([
            'amount' => $this->amount,
            'category' => $this->category,
            'description' => $this->description,
            'expense_date' => $this->expense_date,
            'reference_number' => $this->reference_number,
            'user_id' => auth()->id(), // Assuming user is logged in
        ]);

        notify()->success('Expense recorded successfully.');

        return $this->redirect(route('expenses.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.expenses.create-expense');
    }
}

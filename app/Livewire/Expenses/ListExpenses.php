<?php

namespace App\Livewire\Expenses;

use App\Enums\ExpenseCategory;
use App\Models\Expense;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ListExpenses extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'expense_date';
    public string $sortDirection = 'desc';
    public string $filterCategory = '';
    
    public ?Expense $selectedExpense = null;

    public function viewExpense($id)
    {
        $this->selectedExpense = Expense::find($id);
        $this->dispatch('open-modal', name: 'expense-modal');
    }

    public function closeExpenseModal()
    {
        $this->selectedExpense = null;
        $this->dispatch('close-modal', name: 'expense-modal');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
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
        $expenses = Expense::query()
            ->when($this->search, fn($q) => $q->where('description', 'like', '%'.$this->search.'%')
                ->orWhere('reference_number', 'like', '%'.$this->search.'%'))
            ->when($this->filterCategory, fn($q) => $q->where('category', $this->filterCategory))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.expenses.list-expenses', [
            'expenses' => $expenses,
            'expenseCategories' => ExpenseCategory::cases(),
        ]);
    }
}

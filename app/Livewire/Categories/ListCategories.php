<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ListCategories extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingCategoryId = null;
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';

    #[Validate('required|string|max:255|unique:categories,name')]
    public string $name = '';

    public string $description = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset(['name', 'description', 'editingCategoryId', 'isEditing']);
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
        ]);

        $this->showModal = false;
        notify()->success()->title('Success')->message('Category created successfully.')->send();
        
        return redirect()->route('categories.index');
    }

    public function edit(Category $category)
    {
        $this->resetValidation();
        $this->editingCategoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $this->editingCategoryId,
            'description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($this->editingCategoryId);
        $category->update([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
        ]);

        $this->showModal = false;
        notify()->success()->title('Success')->message('Category updated successfully.')->send();
        
        return redirect()->route('categories.index');
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->exists()) {
             notify()->error()->title('Action Failed')->message('Cannot delete category because it has associated products.')->send();
             return redirect()->route('categories.index');
        }

        $category->delete();
        notify()->success()->title('Success')->message('Category deleted successfully.')->send();
        
        return redirect()->route('categories.index');
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
        $categories = Category::query()
            ->withCount('products')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.categories.list-categories', [
            'categories' => $categories,
        ]);
    }
}

<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ListUsers extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingUserId = null;
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';

    public string $name = '';
    public string $email = '';
    public string $role = 'user'; // Default role
    public string $password = '';
    public string $password_confirmation = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'role', 'editingUserId', 'isEditing']);
        $this->role = 'user'; // Ensure default
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|exists:roles,name',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole($this->role);

        $this->showModal = false;
        notify()->success()->title('Success')->message('User created successfully.')->send();
        
        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        $this->resetValidation();
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()?->name ?? 'user';
        $this->password = '';
        $this->password_confirmation = '';
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function update()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->editingUserId)],
            'role' => 'required|exists:roles,name',
        ];

        // Only validate password if provided
        if (!empty($this->password)) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $this->validate($rules);

        $user = User::findOrFail($this->editingUserId);
        
        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);
        $user->syncRoles([$this->role]);

        $this->showModal = false;
        notify()->success()->title('Success')->message('User updated successfully.')->send();
        
        return redirect()->route('users.index');
    }

    public function deleteUser($id)
    {
        if (\Illuminate\Support\Facades\Auth::id() === $id) {
             notify()->error()->title('Action Failed')->message('You cannot delete your own account.')->send();
             return redirect()->route('users.index');
        }

        $user = User::findOrFail($id);
        $user->delete();
        
        notify()->success()->title('Success')->message('User deleted successfully.')->send();
        
        return redirect()->route('users.index');
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
        $users = User::query()
            ->with('roles') // Eager load roles
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%'))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
            
        $roles = \Spatie\Permission\Models\Role::all();

        return view('livewire.users.list-users', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}

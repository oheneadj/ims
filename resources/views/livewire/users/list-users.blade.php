<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Users</h1>
            <div class="text-sm opacity-60">Manage system access</div>
        </div>
        <button wire:click="create" class="btn btn-primary">
            <span class="icon-[tabler--plus] size-5"></span>
            Add User
        </button>
    </div>

    <!-- Search Card -->
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body py-4">
            <div class="flex flex-wrap items-center gap-4">
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Search users by name or email..." class="input input-bordered w-full md:w-1/3" />
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="bg-base-200">
                        <tr>
                            <th wire:click="sortBy('name')" class="cursor-pointer hover:bg-base-300">
                                <div class="flex items-center gap-1">
                                    Name
                                    @if($sortBy === 'name')
                                        <span
                                            class="icon-[tabler--chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}] size-4"></span>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('email')" class="cursor-pointer hover:bg-base-300">
                                <div class="flex items-center gap-1">
                                    Email
                                    @if($sortBy === 'email')
                                        <span
                                            class="icon-[tabler--chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}] size-4"></span>
                                    @endif
                                </div>
                            </th>
                            <th class="w-24">Role</th>
                            <th wire:click="sortBy('created_at')" class="cursor-pointer hover:bg-base-300">
                                <div class="flex items-center gap-1">
                                    Joined
                                    @if($sortBy === 'created_at')
                                        <span
                                            class="icon-[tabler--chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }}] size-4"></span>
                                    @endif
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-neutral text-neutral-content rounded-full w-8">
                                                <span class="text-xs">{{ $user->initials() }}</span>
                                            </div>
                                        </div>
                                        <div class="font-bold">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->hasRole('super_admin'))
                                        <span class="badge badge-primary badge-sm font-bold">Admin</span>
                                    @else
                                        <span class="badge badge-ghost badge-sm">User</span>
                                    @endif
                                </td>
                                <td class="text-sm opacity-70">{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="flex gap-1">
                                        <button wire:click="edit({{ $user->id }})" class="btn btn-info btn-sm">
                                            <span class="icon-[tabler--pencil] size-4"></span>
                                            Edit
                                        </button>

                                        @if(auth()->id() !== $user->id)
                                            <button class="btn btn-error btn-sm" x-data
                                                x-on:click="$dispatch('open-delete-modal', { id: {{ $user->id }}, name: '{{ addslashes($user->name) }}' })">
                                                <span class="icon-[tabler--trash] size-4"></span>
                                                Delete
                                            </button>
                                        @else
                                            <button class="btn btn-disabled btn-sm" disabled title="You cannot delete yourself">
                                                <span class="icon-[tabler--trash] size-4"></span>
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 opacity-50">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    {{ $users->links() }}

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
            <div class="card bg-base-100 w-full max-w-md shadow-2xl scale-100 transform transition-transform">
                <div class="card-body">
                    <h3 class="card-title text-lg font-bold mb-4">
                        {{ $isEditing ? 'Edit User' : 'Add User' }}
                    </h3>

                    <form wire:submit="{{ $isEditing ? 'update' : 'store' }}">
                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-medium">Name</span>
                            </label>
                            <input wire:model="name" type="text"
                                class="input input-bordered w-full @error('name') input-error @enderror" />
                            @error('name') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-medium">Email</span>
                            </label>
                            <input wire:model="email" type="email"
                                class="input input-bordered w-full @error('email') input-error @enderror" />
                            @error('email') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-medium">Role</span>
                            </label>
                            <select wire:model="role"
                                class="select select-bordered w-full @error('role') select-error @enderror">
                                @foreach($roles as $r)
                                    <option value="{{ $r->name }}">{{ ucwords(str_replace('_', ' ', $r->name)) }}</option>
                                @endforeach
                            </select>
                            @error('role') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-medium">Password
                                    {{ $isEditing ? '(Leave blank to keep current)' : '' }}</span>
                            </label>
                            <input wire:model="password" type="password"
                                class="input input-bordered w-full @error('password') input-error @enderror" />
                            @error('password') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control w-full mb-6">
                            <label class="label">
                                <span class="label-text font-medium">Confirm Password</span>
                            </label>
                            <input wire:model="password_confirmation" type="password" class="input input-bordered w-full" />
                        </div>

                        <div class="card-actions justify-end gap-2">
                            <button type="button" wire:click="$set('showModal', false)"
                                class="btn btn-ghost">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                {{ $isEditing ? 'Update' : 'Create' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div x-data="{ open: false, id: null, name: '' }"
        x-on:open-delete-modal.window="open = true; id = $event.detail.id; name = $event.detail.name" x-show="open"
        style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div class="card bg-base-100 w-full max-w-sm shadow-2xl scale-100 transform transition-transform">
            <div class="card-body text-center">
                <div class="flex justify-center mb-4 text-error">
                    <span class="icon-[tabler--alert-circle] size-16"></span>
                </div>
                <h3 class="text-xl font-bold">Delete User?</h3>
                <p class="py-4 text-base-content/70">
                    Are you sure you want to delete <span class="font-bold text-base-content" x-text="name"></span>?
                    <br>This action cannot be undone.
                </p>
                <div class="card-actions justify-center gap-4">
                    <button @click="open = false" class="btn btn-ghost">Cancel</button>
                    <button @click="open = false; $wire.deleteUser(id)" class="btn btn-error text-white">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
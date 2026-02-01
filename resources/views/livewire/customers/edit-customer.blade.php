<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-base-content">Edit Customer: {{ $customer->name }}</h1>
            <p class="text-base-content/60 mt-1">Update customer details and preferences.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('customers.index') }}" class="btn btn-ghost">Cancel</a>
            <button type="button" wire:click="save" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-2">
                    <path fill-rule="evenodd"
                        d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                        clip-rule="evenodd" />
                </svg>
                Update Customer
            </button>
        </div>
    </div>

    <form wire:submit="save" class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-8 gap-8">

            {{-- Personal Info Section --}}
            <section>
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path
                                d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z" />
                        </svg>
                    </span>
                    Personal Details
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control md:col-span-2">
                        <label class="label"><span class="label-text font-medium">Full Name</span></label>
                        <input wire:model="name" type="text"
                            class="input input-bordered w-full @error('name') input-error @enderror" />
                        @error('name') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Phone Number</span></label>
                        <input wire:model="phone" type="text"
                            class="input input-bordered w-full @error('phone') input-error @enderror" />
                        @error('phone') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Email Address</span></label>
                        <input wire:model="email" type="email"
                            class="input input-bordered w-full @error('email') input-error @enderror" />
                        @error('email') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="label"><span class="label-text font-medium">Address</span></label>
                        <textarea wire:model="address" class="textarea textarea-bordered h-20 text-base"></textarea>
                        @error('address') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </section>

            <div class="divider my-0"></div>

            {{-- Credit Settings Section --}}
            <section>
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path
                                d="M2.5 3A1.5 1.5 0 001 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0115 5.293V4.5A1.5 1.5 0 0013.5 3h-11z" />
                            <path
                                d="M15 6.953l-6.445 3.11a.75.75 0 01-.635 0L1.5 6.953V13.5a2.25 2.25 0 002.25 2.25h9.75a2.25 2.25 0 002.25-2.25V6.953z" />
                        </svg>
                    </span>
                    Credit & Limits
                </h3>
                <div class="form-control border rounded-xl p-4 bg-base-50">
                    <label class="label cursor-pointer justify-between">
                        <span class="font-medium">Enable Credit Purchases</span>
                        <input wire:model.live="is_credit_customer" type="checkbox" class="toggle toggle-primary" />
                    </label>
                    <p class="text-xs text-base-content/60 mt-1 px-1">Allow this customer to make purchases on credit
                        and pay later.</p>
                </div>

                @if($is_credit_customer)
                    <div class="form-control mt-4">
                        <label class="label"><span class="label-text font-medium">Credit Limit ($)</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 opacity-50">$</span>
                            <input wire:model="credit_limit" type="number" step="0.01"
                                class="input input-bordered w-full pl-7 max-w-xs @error('credit_limit') input-error @enderror" />
                        </div>
                        @error('credit_limit') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                        <div class="text-xs opacity-60 mt-2">Maximum outstanding balance allowed for this customer.</div>
                    </div>
                @endif
            </section>

            <div class="divider my-0"></div>

            {{-- Notes Section --}}
            <section>
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-neutral/10 text-neutral flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    Additional Info
                </h3>
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Private Notes</span></label>
                    <textarea wire:model="notes" class="textarea textarea-bordered h-24 text-base"></textarea>
                    @error('notes') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </section>
        </div>
    </form>
</div>
<div class="max-w-2xl mx-auto space-y-6">
    @if($isLocked)
        <!-- Lock Screen -->
        <div class="card bg-base-100 shadow-xl border border-error/20 mt-10">
            <div class="card-body text-center py-10">
                <div class="flex justify-center mb-4 text-error">
                    <span class="icon-[tabler--lock] size-16"></span>
                </div>
                <h2 class="text-2xl font-bold">Authentication Required</h2>
                <div class="text-base-content/70 max-w-sm mx-auto mt-2">
                    This is a sensitive action. Please confirm your password to edit this payment.
                </div>

                <form wire:submit.prevent="unlock" class="max-w-xs mx-auto mt-6 w-full space-y-4">
                    <div class="form-control text-left">
                        <label class="label"><span class="label-text font-medium">Password</span></label>
                        <input type="password" wire:model="passwordConfirmation" autofocus
                            class="input input-bordered w-full @error('passwordConfirmation') input-error @enderror"
                            placeholder="Enter your password..." />
                        @error('passwordConfirmation') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-3 justify-center pt-2">
                        <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-ghost">Cancel</a>
                        <button type="submit" class="btn btn-error text-white w-32">
                            <span class="loading loading-spinner loading-xs" wire:loading target="unlock"></span>
                            Unlock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Edit Payment #{{ $payment->id }}</h1>
                <div class="text-sm opacity-60">Update payment details</div>
            </div>
            <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-ghost">Cancel</a>
        </div>

        <!-- Edit Form -->
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <form wire:submit="update" class="space-y-6">
                    <!-- Read-Only Context -->
                    <div class="grid grid-cols-2 gap-4 bg-base-200 p-4 rounded-lg">
                        <div>
                            <span class="text-xs opacity-60 uppercase block font-bold">Customer</span>
                            <div class="font-medium">{{ $customer_name }}</div>
                        </div>
                        <div>
                            <span class="text-xs opacity-60 uppercase block font-bold">Linked Sale</span>
                            <div class="font-medium">
                                @if($sale_id)
                                    Sale #{{ $sale_id }}
                                @else
                                    <span class="italic opacity-50">General Payment</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Date -->
                        <div class="form-control">
                            <label class="label">Payment Date</label>
                            <input type="date" wire:model="payment_date" class="input input-bordered w-full" />
                            @error('payment_date') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Method -->
                        <div class="form-control">
                            <label class="label">Payment Method</label>
                            <select wire:model="payment_method" class="select select-bordered w-full">
                                <option value="">Select Method</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->value }}">{{ $method->label() }}</option>
                                @endforeach
                            </select>
                            @error('payment_method') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="form-control">
                        <label class="label">Amount Paid (₵)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 opacity-50">₵</span>
                            <input type="number" step="0.01" wire:model="amount"
                                class="input input-bordered w-full pl-8 font-mono text-lg" />
                        </div>
                        @error('amount') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Notes -->
                    <div class="form-control">
                        <label class="label">Notes (Optional)</label>
                        <textarea wire:model="notes" class="textarea textarea-bordered h-24 placeholder:opacity-50"
                            placeholder="Add reference number or details..."></textarea>
                        @error('notes') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end pt-4 border-t border-base-200">
                        <button type="submit" class="btn btn-primary">
                            <span class="loading loading-spinner loading-xs" wire:loading target="update"></span>
                            Update Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
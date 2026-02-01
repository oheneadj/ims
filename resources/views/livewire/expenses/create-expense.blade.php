<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-base-content">Record Expense</h1>
            <p class="text-base-content/60 mt-1">Log a new business expense.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('expenses.index') }}" class="btn btn-ghost">Cancel</a>
            <button type="button" wire:click="save" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-2">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z"
                        clip-rule="evenodd" />
                </svg>
                Save Expense
            </button>
        </div>
    </div>

    <form wire:submit="save" class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-8 gap-8">

            {{-- Expense Details Section --}}
            <section>
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-error/10 text-error flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path
                                d="M10 9a3 3 0 100-6 3 3 0 000 6zM6 8a2 2 0 11-4 0 2 2 0 014 0zM1.49 15.326a.78.78 0 01-.358-.442 3 3 0 014.308-3.516 6.484 6.484 0 00-1.905 3.959c-.023.222-.014.442.025.654a4.97 4.97 0 01-2.07-.655zM16.44 15.98a4.97 4.97 0 002.07-.654.78.78 0 00.357-.442 3 3 0 00-4.308-3.517 6.484 6.484 0 011.907 3.96 2.32 2.32 0 01-.026.654zM18 8a2 2 0 11-4 0 2 2 0 014 0zM5.304 16.19a.844.844 0 01-.277-.71 5 5 0 009.947 0 .843.843 0 01-.277.71A6.975 6.975 0 0110 18a6.974 6.974 0 01-4.696-1.81z" />
                        </svg>
                    </span>
                    Details
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Amount ($)</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 opacity-50">$</span>
                            <input wire:model="amount" type="number" step="0.01"
                                class="input input-bordered w-full pl-7 @error('amount') input-error @enderror"
                                placeholder="0.00" />
                        </div>
                        @error('amount') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Date</span></label>
                        <input wire:model="expense_date" type="date"
                            class="input input-bordered w-full @error('expense_date') input-error @enderror" />
                        @error('expense_date') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Category</span></label>
                        <select wire:model="category" class="select select-bordered w-full">
                            @foreach(\App\Enums\ExpenseCategory::cases() as $category)
                                <option value="{{ $category->value }}">{{ $category->label() }}</option>
                            @endforeach
                        </select>
                        @error('category') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-medium">Reference (Optional)</span></label>
                        <input wire:model="reference_number" type="text" class="input input-bordered w-full"
                            placeholder="Invoice # or Receipt ID" />
                        @error('reference_number') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="label"><span class="label-text font-medium">Description</span></label>
                        <textarea wire:model="description" class="textarea textarea-bordered h-24 text-base"
                            placeholder="e.g., Office Supplies, Restocking Invoice #123"></textarea>
                        @error('description') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </section>

            <div class="divider my-0"></div>

            {{-- Receipt Section --}}
            <section>
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-neutral/10 text-neutral flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd"
                                d="M1 5.25A2.25 2.25 0 013.25 3h13.5A2.25 2.25 0 0119 5.25v9.5A2.25 2.25 0 0116.75 17H3.25A2.25 2.25 0 011 14.75v-9.5zm1.5 5.81v3.69c0 .414.336.75.75.75h13.5a.75.75 0 00.75-.75v-2.69l-2.22-2.219a.75.75 0 00-1.06 0l-1.91 1.909.47.47a.75.75 0 11-1.06 1.06L6 8.06l-3.5 3z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    Receipt Image
                </h3>
                <div class="form-control">
                    <x-filepond wire:model="receipt" />
                    @if ($receipt)
                        <div class="mt-4 p-4 border rounded-xl bg-base-50 inline-block">
                            <p class="text-sm font-medium mb-2 opacity-70">Preview</p>
                            {{-- Check if receipt is image before showing img tag, FilePond handles preview but wire:model
                            sync might show it --}}
                            @if(Str::startsWith($receipt->getMimeType(), 'image'))
                                <img src="{{ $receipt->temporaryUrl() }}" class="w-48 h-48 object-cover rounded-lg shadow-sm" />
                            @else
                                <div class="flex items-center gap-2 p-3 bg-base-200 rounded text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="w-5 h-5 opacity-60">
                                        <path fill-rule="evenodd"
                                            d="M4.5 2A1.5 1.5 0 003 3.5v13A1.5 1.5 0 004.5 18h11a1.5 1.5 0 001.5-1.5V7.621a1.5 1.5 0 00-.44-1.06l-4.12-4.122A1.5 1.5 0 0011.378 2H4.5zm2.25 8.5a.75.75 0 000 1.5h6.5a.75.75 0 000-1.5h-6.5zm0 3a.75.75 0 000 1.5h6.5a.75.75 0 000-1.5h-6.5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $receipt->getClientOriginalName() }}
                                </div>
                            @endif
                        </div>
                    @endif
                    @error('receipt') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </section>
        </div>
    </form>
</div>
<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <h3 class="text-lg font-bold">{{ __('Delete account') }}</h3>
        <p class="text-sm opacity-70">{{ __('Delete your account and all of its resources') }}</p>
    </div>

    <div x-data="{ modalOpen: false }">
        <button class="btn btn-error" @click="modalOpen = true">
            {{ __('Delete account') }}
        </button>

        <div x-show="modalOpen"
            class="fixed inset-0 z-[60] flex items-center justify-center overflow-y-auto overflow-x-hidden p-4 sm:p-0"
            role="dialog" aria-modal="true" style="display: none;">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="modalOpen = false"></div>

            <!-- Modal Panel -->
            <div
                class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg dark:bg-neutral-800">
                <div class="p-6">
                    <form method="POST" wire:submit="deleteUser">
                        <h3 class="font-bold text-lg dark:text-white">
                            {{ __('Are you sure you want to delete your account?') }}</h3>
                        <p class="py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>

                        <div class="form-control w-full">
                            <label class="label"><span class="label-text">Password</span></label>
                            <input wire:model="password" type="password" class="input input-bordered w-full" />
                        </div>

                        <div class="mt-6 flex justify-end gap-3 cancel-btn">
                            <button type="button" class="btn" @click="modalOpen = false">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit" class="btn btn-error">
                                {{ __('Delete account') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<x-layouts.auth>
    <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
        <div class="card-body">
            <x-auth-header :title="__('Confirm password')" :description="__('This is a secure area of the application. Please confirm your password before continuing.')" />

            <x-auth-session-status class="text-center" :status="session('status')" />

            <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-4 mt-4"
                x-data="{ showPassword: false }">
                @csrf

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">{{ __('Password') }}</span></label>
                    <label
                        class="input input-bordered flex items-center gap-2 focus-within:outline-none focus-within:border-primary">
                        <span class="icon-[tabler--lock] opacity-50"></span>
                        <input :type="showPassword ? 'text' : 'password'" name="password" required
                            autocomplete="current-password" placeholder="••••••••" class="grow" />
                        <button type="button" @click="showPassword = !showPassword"
                            class="btn btn-xs btn-circle btn-ghost opacity-70 hover:opacity-100">
                            <span x-show="!showPassword" class="icon-[tabler--eye]"></span>
                            <span x-show="showPassword" class="icon-[tabler--eye-off]" style="display: none;"></span>
                        </button>
                    </label>
                </div>

                <div class="form-control mt-4">
                    <button type="submit" class="btn btn-primary w-full shadow-lg shadow-primary/30"
                        data-test="confirm-password-button">
                        {{ __('Confirm') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.auth>
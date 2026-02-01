<x-layouts.auth>
    <div class="flex flex-col gap-6 w-full max-w-sm mx-auto">
        <x-auth-header :title="__('Confirm password')" :description="__('This is a secure area of the application. Please confirm your password before continuing.')" />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-4">
            @csrf

            <div class="form-control">
                <label class="label"><span class="label-text">{{ __('Password') }}</span></label>
                <input name="password" type="password" required autocomplete="current-password"
                    placeholder="{{ __('Password') }}" class="input input-bordered w-full" />
            </div>

            <div class="form-control mt-2">
                <button type="submit" class="btn btn-primary w-full" data-test="confirm-password-button">
                    {{ __('Confirm') }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.auth>
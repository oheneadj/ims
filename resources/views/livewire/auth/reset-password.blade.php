<x-layouts.auth>
    <div class="flex flex-col gap-6 w-full max-w-sm mx-auto">
        <x-auth-header :title="__('Reset password')" :description="__('Please enter your new password below')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-4">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <div class="form-control">
                <label class="label"><span class="label-text">{{ __('Email') }}</span></label>
                <input name="email" value="{{ request('email') }}" type="email" required autocomplete="email"
                    class="input input-bordered w-full" />
            </div>

            <!-- Password -->
            <div class="form-control">
                <label class="label"><span class="label-text">{{ __('Password') }}</span></label>
                <input name="password" type="password" required autocomplete="new-password"
                    placeholder="{{ __('Password') }}" class="input input-bordered w-full" />
            </div>

            <!-- Confirm Password -->
            <div class="form-control">
                <label class="label"><span class="label-text">{{ __('Confirm password') }}</span></label>
                <input name="password_confirmation" type="password" required autocomplete="new-password"
                    placeholder="{{ __('Confirm password') }}" class="input input-bordered w-full" />
            </div>

            <div class="form-control mt-2">
                <button type="submit" class="btn btn-primary w-full" data-test="reset-password-button">
                    {{ __('Reset password') }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.auth>
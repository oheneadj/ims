<x-layouts.auth>
    <div class="flex flex-col gap-6 w-full max-w-sm mx-auto">
        <x-auth-header :title="__('Forgot password')" :description="__('Enter your email to receive a password reset link')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-4">
            @csrf

            <!-- Email Address -->
            <div class="form-control">
                <label class="label"><span class="label-text">{{ __('Email Address') }}</span></label>
                <input name="email" type="email" required autofocus placeholder="email@example.com"
                    class="input input-bordered w-full" />
            </div>

            <div class="form-control mt-2">
                <button type="submit" class="btn btn-primary w-full" data-test="email-password-reset-link-button">
                    {{ __('Email password reset link') }}
                </button>
            </div>
        </form>

        <div class="text-center text-sm opacity-70">
            <span>{{ __('Or, return to') }}</span>
            <a href="{{ route('login') }}" wire:navigate
                class="link link-primary no-underline hover:underline font-bold">{{ __('log in') }}</a>
        </div>
    </div>
</x-layouts.auth>
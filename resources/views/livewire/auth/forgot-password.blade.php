<x-layouts.auth>
    <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
        <div class="card-body">
            <x-auth-header :title="__('Forgot password')" :description="__('Enter your email to receive a password reset link')" />

            <!-- Session Status -->
            <x-auth-session-status class="text-center" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-4 mt-4">
                @csrf

                <!-- Email Address -->
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">{{ __('Email Address') }}</span></label>
                    <label
                        class="input input-bordered flex items-center gap-2 focus-within:outline-none focus-within:border-primary">
                        <span class="icon-[tabler--mail] opacity-50"></span>
                        <input name="email" type="email" required autofocus placeholder="you@example.com"
                            class="grow" />
                    </label>
                </div>

                <div class="form-control mt-4">
                    <button type="submit" class="btn btn-primary w-full shadow-lg shadow-primary/30"
                        data-test="email-password-reset-link-button">
                        {{ __('Email password reset link') }}
                    </button>
                </div>
            </form>

            <div class="divider my-4 text-xs opacity-50">OR</div>
            <div class="text-center text-sm">
                <span class="opacity-70">{{ __('Return to') }}</span>
                <a href="{{ route('login') }}" wire:navigate
                    class="link link-primary font-bold no-underline hover:underline ml-1">{{ __('Log in') }}</a>
            </div>
        </div>
    </div>
</x-layouts.auth>
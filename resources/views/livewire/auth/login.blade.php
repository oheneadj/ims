<x-layouts.auth>
    <div class="flex flex-col gap-6 w-full max-w-sm mx-auto">
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4">
            @csrf

            <!-- Email Address -->
            <div class="form-control">
                <label class="label"><span class="label-text">{{ __('Email address') }}</span></label>
                <input name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                    placeholder="email@example.com" class="input input-bordered w-full" />
            </div>

            <!-- Password -->
            <div class="form-control">
                <label class="label justify-between cursor-pointer">
                    <span class="label-text">{{ __('Password') }}</span>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate
                            class="label-text-alt link link-primary no-underline hover:underline">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </label>
                <input name="password" type="password" required autocomplete="current-password"
                    placeholder="{{ __('Password') }}" class="input input-bordered w-full" />
            </div>

            <!-- Remember Me -->
            <div class="form-control">
                <label class="label cursor-pointer justify-start gap-3">
                    <input name="remember" type="checkbox" class="checkbox checkbox-sm" {{ old('remember') ? 'checked' : '' }} />
                    <span class="label-text">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="form-control mt-2">
                <button type="submit" class="btn btn-primary w-full" data-test="login-button">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="text-center text-sm opacity-70">
                <span>{{ __('Don\'t have an account?') }}</span>
                <a href="{{ route('register') }}" wire:navigate
                    class="link link-primary no-underline hover:underline font-bold">{{ __('Sign up') }}</a>
            </div>
        @endif
    </div>
</x-layouts.auth>
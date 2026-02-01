<x-layouts.auth>
    <div class="flex flex-col gap-6 w-full max-w-sm mx-auto">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-4">
            @csrf
            <!-- Name -->
            <div class="form-control">
                <label class="label"><span class="label-text">{{ __('Name') }}</span></label>
                <input name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                    placeholder="{{ __('Full name') }}" class="input input-bordered w-full" />
            </div>

            <!-- Email Address -->
            <div class="form-control">
                <label class="label"><span class="label-text">{{ __('Email address') }}</span></label>
                <input name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                    placeholder="email@example.com" class="input input-bordered w-full" />
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
                <button type="submit" class="btn btn-primary w-full" data-test="register-user-button">
                    {{ __('Create account') }}
                </button>
            </div>
        </form>

        <div class="text-center text-sm opacity-70">
            <span>{{ __('Already have an account?') }}</span>
            <a href="{{ route('login') }}" wire:navigate
                class="link link-primary no-underline hover:underline font-bold">{{ __('Log in') }}</a>
        </div>
    </div>
</x-layouts.auth>
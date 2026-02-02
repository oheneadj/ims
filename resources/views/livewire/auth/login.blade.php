<x-layouts.auth>
    <div class="card bg-base-100 w-full ">
        <div class="card-body">
            <x-auth-header :title="__('Welcome back!')" :description="__('Sign in to access your account')" />

            <!-- Session Status -->
            <x-auth-session-status class="text-center" :status="session('status')" />

            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4 mt-4"
                x-data="{ showPassword: false }">
                @csrf

                <!-- Email Address -->
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">{{ __('Email') }}</span></label>
                    <label
                        class="input input-bordered flex items-center gap-2 focus-within:outline-none focus-within:border-primary">
                        <span class="icon-[tabler--mail] opacity-50"></span>
                        <input name="email" type="email" value="{{ old('email') }}" required autofocus
                            autocomplete="email" placeholder="you@example.com" class="grow" />
                    </label>
                </div>

                <!-- Password -->
                <div class="form-control">
                    <label class="label justify-between cursor-pointer">
                        <span class="label-text font-bold">{{ __('Password') }}</span>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" wire:navigate
                                class="label-text-alt link link-primary no-underline hover:underline">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </label>
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

                <!-- Remember Me -->
                <div class="form-control">
                    <label class="label cursor-pointer justify-start gap-3">
                        <input name="remember" type="checkbox" class="checkbox checkbox-sm checkbox-primary" {{ old('remember') ? 'checked' : '' }} />
                        <span class="label-text font-medium">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="form-control mt-4">
                    <button type="submit" class="btn btn-primary w-full shadow-lg shadow-primary/30"
                        data-test="login-button">
                        {{ __('Log in') }}
                        <span class="icon-[tabler--arrow-right]"></span>
                    </button>
                </div>
            </form>

            @if (Route::has('register'))

                <div class="text-center text-sm mt-4">
                    <span class="opacity-70">{{ __('All rights reserved by') }}</span>
                    <a target="_blank" href="https://donielgroup.com" wire:navigate
                        class="link link-primary font-bold no-underline hover:underline ml-1">{{ __('(c) Doniel Group') }}</a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.auth>
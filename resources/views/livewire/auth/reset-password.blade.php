<x-layouts.auth>
    <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
        <div class="card-body">
            <x-auth-header :title="__('Reset password')" :description="__('Please enter your new password below')" />

            <!-- Session Status -->
            <x-auth-session-status class="text-center" :status="session('status')" />

            <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-4 mt-4"
                x-data="{ showPassword: false, showConfirmPassword: false }">
                @csrf
                <!-- Token -->
                <input type="hidden" name="token" value="{{ request()->route('token') }}">

                <!-- Email Address -->
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">{{ __('Email') }}</span></label>
                    <label
                        class="input input-bordered flex items-center gap-2 focus-within:outline-none focus-within:border-primary">
                        <span class="icon-[tabler--mail] opacity-50"></span>
                        <input name="email" value="{{ request('email') }}" type="email" required autocomplete="email"
                            class="grow" />
                    </label>
                </div>

                <!-- Password -->
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">{{ __('Password') }}</span></label>
                    <label
                        class="input input-bordered flex items-center gap-2 focus-within:outline-none focus-within:border-primary">
                        <span class="icon-[tabler--lock] opacity-50"></span>
                        <input :type="showPassword ? 'text' : 'password'" name="password" required
                            autocomplete="new-password" placeholder="••••••••" class="grow" />
                        <button type="button" @click="showPassword = !showPassword"
                            class="btn btn-xs btn-circle btn-ghost opacity-70 hover:opacity-100">
                            <span x-show="!showPassword" class="icon-[tabler--eye]"></span>
                            <span x-show="showPassword" class="icon-[tabler--eye-off]" style="display: none;"></span>
                        </button>
                    </label>
                </div>

                <!-- Confirm Password -->
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">{{ __('Confirm Password') }}</span></label>
                    <label
                        class="input input-bordered flex items-center gap-2 focus-within:outline-none focus-within:border-primary">
                        <span class="icon-[tabler--lock-check] opacity-50"></span>
                        <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required
                            autocomplete="new-password" placeholder="••••••••" class="grow" />
                        <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                            class="btn btn-xs btn-circle btn-ghost opacity-70 hover:opacity-100">
                            <span x-show="!showConfirmPassword" class="icon-[tabler--eye]"></span>
                            <span x-show="showConfirmPassword" class="icon-[tabler--eye-off]"
                                style="display: none;"></span>
                        </button>
                    </label>
                </div>

                <div class="form-control mt-4">
                    <button type="submit" class="btn btn-primary w-full shadow-lg shadow-primary/30"
                        data-test="reset-password-button">
                        {{ __('Reset password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.auth>
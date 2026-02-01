<x-layouts.auth>
    <div class="flex flex-col gap-6 w-full max-w-sm mx-auto">
        <div class="relative w-full h-auto" x-cloak x-data="{
                showRecoveryInput: @js($errors->has('recovery_code')),
                code: '',
                recovery_code: '',
                toggleInput() {
                    this.showRecoveryInput = !this.showRecoveryInput;
                    this.code = '';
                    this.recovery_code = '';
                    $nextTick(() => {
                        this.showRecoveryInput
                            ? this.$refs.recovery_code?.focus()
                            : this.$refs.code?.focus();
                    });
                },
            }">
            <div x-show="!showRecoveryInput">
                <x-auth-header :title="__('Authentication Code')" :description="__('Enter the authentication code provided by your authenticator application.')" />
            </div>

            <div x-show="showRecoveryInput">
                <x-auth-header :title="__('Recovery Code')" :description="__('Please confirm access to your account by entering one of your emergency recovery codes.')" />
            </div>

            <form method="POST" action="{{ route('two-factor.login.store') }}" class="mt-6">
                @csrf

                <div class="space-y-5 text-center">
                    <div x-show="!showRecoveryInput" class="form-control">
                        <label class="label justify-center"><span class="label-text sr-only">OTP Code</span></label>
                        <input type="text" x-model="code" name="code" inputmode="numeric" autocomplete="one-time-code"
                            x-ref="code" placeholder="Checking app code..."
                            class="input input-bordered w-full text-center tracking-widest text-2xl" />
                    </div>

                    <div x-show="showRecoveryInput" class="form-control">
                        <label class="label"><span class="label-text">Recovery Code</span></label>
                        <input type="text" name="recovery_code" x-ref="recovery_code"
                            x-bind:required="showRecoveryInput" autocomplete="one-time-code" x-model="recovery_code"
                            class="input input-bordered w-full" />
                        @error('recovery_code')
                            <span class="text-error text-sm mt-1">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-full">
                        {{ __('Continue') }}
                    </button>
                </div>

                <div class="mt-5 space-x-0.5 text-sm leading-5 text-center">
                    <span class="opacity-50">{{ __('or you can') }}</span>
                    <button type="button" class="link link-primary no-underline hover:underline" @click="toggleInput()">
                        <span x-show="!showRecoveryInput">{{ __('login using a recovery code') }}</span>
                        <span x-show="showRecoveryInput">{{ __('login using an authentication code') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.auth>
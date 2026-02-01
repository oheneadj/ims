<section class="w-full">
    <x-settings.layout
        :heading="__('Two Factor Authentication')"
        :subheading="__('Manage your two-factor authentication settings')"
    >
        <div class="flex flex-col w-full mx-auto space-y-6 text-sm" wire:cloak>
            @if ($twoFactorEnabled)
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="badge badge-success">{{ __('Enabled') }}</div>
                    </div>

                    <p>
                        {{ __('With two-factor authentication enabled, you will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.') }}
                    </p>

                    <livewire:settings.two-factor.recovery-codes :$requiresConfirmation/>

                    <div class="flex justify-start">
                        <button
                            class="btn btn-error btn-outline"
                            wire:click="disable"
                        >
                            {{ __('Disable 2FA') }}
                        </button>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="badge badge-error">{{ __('Disabled') }}</div>
                    </div>

                    <p class="opacity-70">
                        {{ __('When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.') }}
                    </p>

                    <button
                        class="btn btn-primary"
                        wire:click="enable"
                    >
                        {{ __('Enable 2FA') }}
                    </button>
                </div>
            @endif
        </div>
    </x-settings.layout>

    <!-- Modal for 2FA Setup -->
    <!-- Modal for 2FA Setup -->
    @if($showModal)
    <div class="fixed inset-0 z-[60] flex items-center justify-center overflow-y-auto overflow-x-hidden p-4 sm:p-0" aria-labelledby="two_factor_setup_modal" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

        <!-- Modal Panel -->
        <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg dark:bg-neutral-800">
            <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                 <!-- Modal Title -->
                 <h3 class="font-bold text-lg mb-4 text-center dark:text-white">{{ $this->modalConfig['title'] ?? '' }}</h3>
                 <p class="text-sm text-center mb-6 text-gray-500 dark:text-gray-400">{{ $this->modalConfig['description'] ?? '' }}</p>
    
                <div class="space-y-6">
                     @if ($showVerificationStep)
                        <div class="space-y-6">
                            <div class="flex flex-col items-center space-y-3 justify-center">
                                <div class="form-control w-full max-w-xs">
                                    <label class="label"><span class="label-text">OTP Code</span></label>
                                    <input
                                        type="text"
                                        wire:model="code"
                                        class="input input-bordered text-center tracking-widest text-2xl"
                                        placeholder="000000"
                                        maxlength="6"
                                    />
                                </div>
                            </div>
    
                            <div class="flex items-center space-x-3">
                                <button
                                    class="btn btn-outline flex-1"
                                    wire:click="resetVerification"
                                >
                                    {{ __('Back') }}
                                </button>
    
                                <button
                                    class="btn btn-primary flex-1"
                                    wire:click="confirmTwoFactor"
                                    @if(strlen($code ?? '') < 6) disabled @endif
                                >
                                    {{ __('Confirm') }}
                                </button>
                            </div>
                        </div>
                    @else
                        @error('setupData')
                            <div class="alert alert-error">
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
    
                        <div class="flex justify-center">
                             <div class="relative w-64 overflow-hidden border rounded-lg border-base-300 aspect-square flex items-center justify-center bg-white p-4">
                                @if(empty($qrCodeSvg))
                                    <span class="loading loading-spinner loading-lg"></span>
                                @else
                                    {!! $qrCodeSvg !!}
                                @endif
                            </div>
                        </div>
    
                        <div>
                            <button
                                @if($errors->has('setupData')) disabled @endif
                                class="btn btn-primary w-full"
                                wire:click="showVerificationIfNecessary"
                            >
                                {{ $this->modalConfig['buttonText'] ?? '' }}
                            </button>
                        </div>
    
                        <div class="space-y-4">
                            <div class="relative flex items-center justify-center w-full">
                                <div class="absolute inset-0 w-full h-px top-1/2 bg-base-300"></div>
                                <span class="relative px-2 text-sm bg-base-100 opacity-70">
                                    {{ __('or, enter the code manually') }}
                                </span>
                            </div>
    
                             <div
                                class="flex items-center gap-2"
                                x-data="{
                                    copied: false,
                                    async copy() {
                                        try {
                                            await navigator.clipboard.writeText('{{ $manualSetupKey }}');
                                            this.copied = true;
                                            setTimeout(() => this.copied = false, 1500);
                                        } catch (e) {
                                            console.warn('Could not copy to clipboard');
                                        }
                                    }
                                }"
                            >
                                 <div class="join w-full">
                                    <input
                                        type="text"
                                        readonly
                                        value="{{ $manualSetupKey }}"
                                        class="input input-bordered join-item w-full"
                                    />
                                    <button
                                        @click="copy()"
                                        class="btn join-item"
                                    >
                                        <span x-show="!copied">Copy</span>
                                        <span x-show="copied" class="text-success">Copied!</span>
                                    </button>
                                 </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 dark:bg-neutral-800/50">
                <button type="button" class="btn" wire:click="closeModal">Close</button>
            </div>
        </div>
    </div>
    @endif
</section>

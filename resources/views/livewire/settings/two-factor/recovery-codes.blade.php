<div class="py-6 space-y-6 border shadow-sm rounded-xl border-base-300 bg-base-100" wire:cloak
    x-data="{ showRecoveryCodes: false }">
    <div class="px-6 space-y-2">
        <div class="flex items-center gap-2">
            <!-- Lock Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
            <h3 class="text-lg font-bold">{{ __('2FA Recovery Codes') }}</h3>
        </div>
        <p class="text-sm opacity-70">
            {{ __('Recovery codes let you regain access if you lose your 2FA device. Store them in a secure password manager.') }}
        </p>
    </div>

    <div class="px-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <button x-show="!showRecoveryCodes" class="btn btn-primary btn-sm" @click="showRecoveryCodes = true;">
                {{ __('View Recovery Codes') }}
            </button>

            <button x-show="showRecoveryCodes" class="btn btn-primary btn-sm btn-outline"
                @click="showRecoveryCodes = false">
                {{ __('Hide Recovery Codes') }}
            </button>

            @if (filled($recoveryCodes))
                <button x-show="showRecoveryCodes" class="btn btn-sm" wire:click="regenerateRecoveryCodes">
                    {{ __('Regenerate Codes') }}
                </button>
            @endif
        </div>

        <div x-show="showRecoveryCodes" x-transition class="mt-4">
            <div class="space-y-3">
                @error('recoveryCodes')
                    <div class="alert alert-error">
                        <span>{{$message}}</span>
                    </div>
                @enderror

                @if (filled($recoveryCodes))
                    <div class="grid gap-1 p-4 font-mono text-sm rounded-lg bg-base-200">
                        @foreach($recoveryCodes as $code)
                            <div class="select-text">
                                {{ $code }}
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs opacity-60">
                        {{ __('Each recovery code can be used once to access your account and will be removed after use. If you need more, click Regenerate Codes above.') }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
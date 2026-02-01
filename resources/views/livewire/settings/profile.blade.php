<section class="w-full">
    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <div class="form-control">
                <label class="label"><span class="label-text">{{ __('Name') }}</span></label>
                <input wire:model="name" type="text" required autofocus autocomplete="name"
                    class="input input-bordered w-full" />
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">{{ __('Email') }}</span></label>
                <input wire:model="email" type="email" required autocomplete="email"
                    class="input input-bordered w-full" />

                @if ($this->hasUnverifiedEmail)
                    <div class="mt-2">
                        <p class="text-sm">
                            {{ __('Your email address is unverified.') }}
                            <button class="link link-primary no-underline hover:underline cursor-pointer"
                                wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm font-medium text-success">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <button type="submit" class="btn btn-primary w-full">{{ __('Save') }}</button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:settings.delete-user-form />
        @endif
    </x-settings.layout>
</section>
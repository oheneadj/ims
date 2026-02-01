<section class="w-full">
    <x-settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <div class="form-control">
                <label class="label"><span class="label-text">{{ __('Current password') }}</span></label>
                <input wire:model="current_password" type="password" required autocomplete="current-password"
                    class="input input-bordered w-full" />
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">{{ __('New password') }}</span></label>
                <input wire:model="password" type="password" required autocomplete="new-password"
                    class="input input-bordered w-full" />
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">{{ __('Confirm Password') }}</span></label>
                <input wire:model="password_confirmation" type="password" required autocomplete="new-password"
                    class="input input-bordered w-full" />
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <button type="submit" class="btn btn-primary w-full">{{ __('Save') }}</button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
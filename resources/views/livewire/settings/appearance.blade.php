<section class="w-full">
    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <div class="flex flex-col gap-4 mt-6">
            <div class="form-control">
                <label class="label cursor-pointer justify-start gap-4">
                    <input type="radio" name="appearance" class="radio" value="light" x-model="$flux.appearance" />
                    <span class="label-text">{{ __('Light') }}</span>
                </label>
            </div>
            <div class="form-control">
                <label class="label cursor-pointer justify-start gap-4">
                    <input type="radio" name="appearance" class="radio" value="dark" x-model="$flux.appearance" />
                    <span class="label-text">{{ __('Dark') }}</span>
                </label>
            </div>
            <div class="form-control">
                <label class="label cursor-pointer justify-start gap-4">
                    <input type="radio" name="appearance" class="radio" value="system" x-model="$flux.appearance" />
                    <span class="label-text">{{ __('System') }}</span>
                </label>
            </div>
        </div>
    </x-settings.layout>
</section>
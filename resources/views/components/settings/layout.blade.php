<div class="flex items-start max-md:flex-col gap-6">
    <div class="w-full md:w-[220px]">
        <ul class="menu bg-base-100 w-full rounded-box">
            <li>
                <h2 class="menu-title">{{ __('Settings') }}</h2>
            </li>
            <li><a href="{{ route('profile.edit') }}" wire:navigate
                    class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">{{ __('Profile') }}</a></li>
            <li><a href="{{ route('user-password.edit') }}" wire:navigate
                    class="{{ request()->routeIs('user-password.edit') ? 'active' : '' }}">{{ __('Password') }}</a></li>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <li><a href="{{ route('two-factor.show') }}" wire:navigate
                        class="{{ request()->routeIs('two-factor.show') ? 'active' : '' }}">{{ __('Two-Factor Auth') }}</a>
                </li>
            @endif
            <li><a href="{{ route('appearance.edit') }}" wire:navigate
                    class="{{ request()->routeIs('appearance.edit') ? 'active' : '' }}">{{ __('Appearance') }}</a></li>
        </ul>
    </div>

    <div class="divider md:hidden"></div>

    <div class="flex-1 w-full">
        <div class="mb-4">
            <h2 class="text-2xl font-bold">{{ $heading ?? '' }}</h2>
            <p class="text-sm opacity-70">{{ $subheading ?? '' }}</p>
        </div>

        <div class="w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
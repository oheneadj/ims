<div class="dropdown dropdown-end">
    <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar placeholder" data-test="sidebar-menu-button">
        <div class="bg-neutral text-neutral-content rounded-full w-10">
            <span>{{ auth()->user()->initials() }}</span>
        </div>
    </div>
    <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
        <li class="menu-title px-4 py-2">
            <div class="font-bold truncate">{{ auth()->user()->name }}</div>
            <div class="text-xs font-normal opacity-70 truncate">{{ auth()->user()->email }}</div>
        </li>
        <li><a href="{{ route('profile.edit') }}" wire:navigate>{{ __('Settings') }}</a></li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left" data-test="logout-button">{{ __('Log Out') }}</button>
            </form>
        </li>
    </ul>
</div>
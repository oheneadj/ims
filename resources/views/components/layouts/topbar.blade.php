<div class="bg-base-100 border-b border-base-content/10 lg:ps-72 sticky top-0 z-30 flex h-16">
    <div class="flex-1 w-full px-4">
        <nav class="flex items-center justify-between h-full py-2">
            {{-- Left Section --}}
            <div class="flex items-center gap-2">
                <button type="button" class="btn btn-ghost btn-square btn-sm lg:hidden"
                    @click.stop="$dispatch('open-sidebar')">
                    <span class="icon-[tabler--menu-2] size-5"></span>
                </button>

                {{-- Page Title --}}
                <h1 class="text-lg font-semibold text-base-content hidden md:block">
                    {{ $header ?? 'Dashboard' }}
                </h1>
            </div>

            {{-- Right Section --}}
            <div class="flex items-center gap-3">
                {{-- Search --}}
                <div class="hidden lg:flex items-center bg-base-200/50 rounded-lg px-3 py-2 gap-2">
                    <span class="icon-[tabler--search] text-base-content/60 size-4 shrink-0"></span>
                    <input type="search"
                        class="bg-transparent border-none outline-none text-sm w-40 focus:w-56 transition-all placeholder:text-base-content/50"
                        placeholder="Search...">
                </div>

                {{-- Notifications Dropdown (CSS-only with details/summary) --}}
                <details class="dropdown dropdown-end">
                    <summary class="btn btn-circle btn-ghost btn-sm list-none cursor-pointer">
                        <div class="indicator">
                            <span class="icon-[tabler--bell] size-5"></span>
                            @php
                                $notificationCount = \App\Models\Product::where('quantity_in_stock', '<=', 5)->count();
                            @endphp
                            @if($notificationCount > 0)
                                <span class="badge badge-xs badge-error indicator-item">{{ $notificationCount }}</span>
                            @endif
                        </div>
                    </summary>
                    <div
                        class="dropdown-content bg-base-100 w-80 max-sm:w-72 shadow-xl rounded-lg mt-2 z-50 border border-base-200">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-base-content/10">
                            <span class="font-semibold text-base-content">Notifications</span>
                            <a href="#" class="text-xs text-primary hover:underline">Mark all read</a>
                        </div>
                        <div class="max-h-72 overflow-y-auto">
                            @php
                                $lowStockProducts = \App\Models\Product::where('quantity_in_stock', '<=', 5)->take(4)->get();
                            @endphp
                            @forelse($lowStockProducts as $product)
                                <a href="{{ route('products.index') }}"
                                    class="flex items-start gap-3 px-4 py-3 hover:bg-base-200/50 transition-colors">
                                    <div
                                        class="flex items-center justify-center w-9 h-9 rounded-full bg-error/10 text-error shrink-0">
                                        <span class="icon-[tabler--alert-triangle] size-4"></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-base-content">Low Stock</p>
                                        <p class="text-xs text-base-content/60 truncate">{{ $product->name }} -
                                            {{ $product->quantity_in_stock }} left
                                        </p>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-6 text-base-content/50">
                                    <span class="icon-[tabler--check] size-8 mb-2 block mx-auto text-success"></span>
                                    <span class="text-sm">All caught up!</span>
                                </div>
                            @endforelse
                        </div>
                        <div class="px-4 py-2 border-t border-base-content/10">
                            <a href="#" class="text-xs text-primary hover:underline">View all notifications</a>
                        </div>
                    </div>
                </details>

                {{-- Profile Dropdown (CSS-only with details/summary) --}}
                <details class="dropdown dropdown-end">
                    <summary class="btn btn-ghost btn-sm gap-2 list-none cursor-pointer">
                        <div
                            class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center">
                            <span
                                class="text-sm font-bold text-white">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                        </div>
                    </summary>
                    <ul
                        class="dropdown-content menu bg-base-100 rounded-lg w-56 shadow-xl border border-base-200 z-50 mt-2 p-1">
                        {{-- User Info --}}
                        <li class="menu-disabled">
                            <div class="flex items-center gap-3 py-2">
                                <div
                                    class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center shrink-0">
                                    <span
                                        class="text-lg font-bold text-white">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-base-content font-semibold text-sm truncate">
                                        {{ auth()->user()->name ?? 'User' }}
                                    </p>
                                    <p class="text-base-content/60 text-xs truncate">{{ auth()->user()->email ??
                                        'admin@ims.com' }}</p>
                                </div>
                            </div>
                        </li>
                        <li class="my-1">
                            <hr class="border-base-content/10">
                        </li>
                        <li>
                            <a href="{{ Route::has('profile.show') ? route('profile.show') : '#' }}">
                                <span class="icon-[tabler--user] size-5"></span>
                                My Profile
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="icon-[tabler--settings] size-5"></span>
                                Settings
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="icon-[tabler--help-circle] size-5"></span>
                                Help & Support
                            </a>
                        </li>
                        <li class="my-1">
                            <hr class="border-base-content/10">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="p-0">
                                @csrf
                                <button type="submit" class="text-error w-full text-left flex items-center gap-2">
                                    <span class="icon-[tabler--logout] size-5"></span>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </details>
            </div>
        </nav>
    </div>
</div>
{{-- Bottom Navigation for Mobile --}}
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-base-100 border-t border-base-200 shadow-lg">
    <div class="flex items-end justify-around h-16 px-2">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
            class="flex flex-col items-center justify-center py-2 px-3 min-w-[60px] {{ request()->routeIs('dashboard') ? 'text-brand-600' : 'text-base-content/60 hover:text-base-content' }}">
            <span class="icon-[tabler--layout-dashboard] size-6 mb-1"></span>
            <span class="text-[10px] font-medium">Home</span>
        </a>

        {{-- Products --}}
        <a href="{{ route('products.index') }}"
            class="flex flex-col items-center justify-center py-2 px-3 min-w-[60px] {{ request()->routeIs('products.*') ? 'text-brand-600' : 'text-base-content/60 hover:text-base-content' }}">
            <span class="icon-[tabler--package] size-6 mb-1"></span>
            <span class="text-[10px] font-medium">Products</span>
        </a>

        {{-- New Sale (Center/Primary - Floating Action Button) --}}
        <a href="{{ route('sales.create') }}"
            class="flex items-center justify-center w-14 h-14 -mt-6 bg-gradient-to-br from-brand-500 to-brand-700 text-white rounded-full shadow-xl hover:shadow-2xl transition-all hover:scale-105">
            <span class="icon-[tabler--plus] size-7"></span>
        </a>

        {{-- Sales --}}
        <a href="{{ route('sales.index') }}"
            class="flex flex-col items-center justify-center py-2 px-3 min-w-[60px] {{ request()->routeIs('sales.index') || request()->routeIs('sales.show') ? 'text-brand-600' : 'text-base-content/60 hover:text-base-content' }}">
            <span class="icon-[tabler--receipt] size-6 mb-1"></span>
            <span class="text-[10px] font-medium">Sales</span>
        </a>

        {{-- More (Menu) --}}
        <button type="button" @click.stop="$dispatch('open-sidebar')"
            class="flex flex-col items-center justify-center py-2 px-3 min-w-[60px] text-base-content/60 hover:text-base-content">
            <span class="icon-[tabler--menu-2] size-6 mb-1"></span>
            <span class="text-[10px] font-medium">Menu</span>
        </button>
    </div>
</nav>
<div x-data="{ isOpen: false }" @open-sidebar.window="isOpen = true" @close-sidebar.window="isOpen = false">

    {{-- Mobile Backdrop --}}
    <div x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="isOpen = false"
        class="fixed inset-0 bg-brand-950/60 backdrop-blur-sm z-[55] lg:hidden" x-cloak>
    </div>

    <aside id="application-sidebar"
        class="fixed top-0 start-0 bottom-0 z-[60] w-72 bg-brand-950 overflow-y-auto transition-all duration-300 transform lg:translate-x-0 lg:static lg:block"
        :class="isOpen ? 'translate-x-0 block' : '-translate-x-full hidden lg:block'" @click.away="isOpen = false"
        x-cloak>

        <div class="flex flex-col h-full min-h-screen">
            {{-- Brand --}}
            <div class="h-20 flex items-center justify-between px-6 border-b border-brand-800/50">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white flex items-center justify-center font-bold text-xl shadow-lg shadow-brand-900/50">
                        D
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-black tracking-tight leading-none text-white">Doniel<span
                                class="text-brand-400">Luxe</span></span>
                        <span
                            class="text-[0.65rem] font-medium uppercase tracking-widest text-brand-300/60 mt-1">Accessories
                            IMS</span>
                    </div>
                </div>

                {{-- Close Button (Mobile Only) --}}
                <button type="button" @click="isOpen = false"
                    class="lg:hidden h-10 w-10 flex items-center justify-center rounded-lg text-brand-300 hover:bg-brand-900/50 hover:text-white transition-colors"
                    aria-label="Close sidebar">
                    <span class="icon-[tabler--x] size-6"></span>
                </button>
            </div>

            {{-- Menu --}}
            <div class="flex-1 overflow-y-auto py-6 px-3">
                <ul class="menu w-full gap-1">
                    {{-- Dashboard --}}
                    <li>
                        <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                            {{ request()->routeIs('dashboard')
    ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/50'
    : 'text-brand-200 hover:bg-brand-900/50 hover:text-white' }}">
                            <span
                                class="icon-[tabler--layout-dashboard] size-5 {{ request()->routeIs('dashboard') ? '' : 'group-hover:scale-110' }} transition-transform"></span>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>

                    {{-- Inventory Section --}}
                    <li class="mt-6 mb-2">
                        <span class="text-[0.65rem] font-bold uppercase tracking-widest text-brand-400/50 px-4">
                            Inventory & Stock
                        </span>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                            {{ request()->routeIs('products.*')
    ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/50'
    : 'text-brand-200 hover:bg-brand-900/50 hover:text-white' }}">
                            <span
                                class="icon-[tabler--package] size-5 {{ request()->routeIs('products.*') ? '' : 'group-hover:scale-110' }} transition-transform"></span>
                            <span class="font-medium">Products</span>
                            @if(isset($lowStockCount) && $lowStockCount > 0)
                                <span class="badge bg-red-500 text-white badge-sm ml-auto">{{ $lowStockCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                            {{ request()->routeIs('categories.*')
    ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/50'
    : 'text-brand-200 hover:bg-brand-900/50 hover:text-white' }}">
                            <span
                                class="icon-[tabler--category] size-5 {{ request()->routeIs('categories.*') ? '' : 'group-hover:scale-110' }} transition-transform"></span>
                            <span class="font-medium">Categories</span>
                        </a>
                    </li>

                    {{-- Commercial Section --}}
                    <li class="mt-6 mb-2">
                        <span class="text-[0.65rem] font-bold uppercase tracking-widest text-brand-400/50 px-4">
                            Commercial
                        </span>
                    </li>
                    <li>
                        <a href="{{ route('sales.create') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                            {{ request()->routeIs('sales.create')
    ? 'bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-lg shadow-brand-900/50'
    : 'bg-brand-700/40 text-brand-300 hover:bg-brand-600 hover:text-white' }}">
                            <span
                                class="icon-[tabler--plus] size-5 {{ request()->routeIs('sales.create') ? '' : 'group-hover:rotate-90' }} transition-transform"></span>
                            <span class="font-semibold">New Sale (POS)</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('sales.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                            {{ request()->routeIs('sales.index') || request()->routeIs('sales.show')
    ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/50'
    : 'text-brand-200 hover:bg-brand-900/50 hover:text-white' }}">
                            <span
                                class="icon-[tabler--file-invoice] size-5 {{ request()->routeIs('sales.index') || request()->routeIs('sales.show') ? '' : 'group-hover:scale-110' }} transition-transform"></span>
                            <span class="font-medium">Sales History</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('customers.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                            {{ request()->routeIs('customers.*')
    ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/50'
    : 'text-brand-200 hover:bg-brand-900/50 hover:text-white' }}">
                            <span
                                class="icon-[tabler--users-group] size-5 {{ request()->routeIs('customers.*') ? '' : 'group-hover:scale-110' }} transition-transform"></span>
                            <span class="font-medium">Customers</span>
                            @if(isset($totalCustomers))
                                <span
                                    class="badge bg-brand-800 text-brand-300 badge-sm ml-auto">{{ $totalCustomers }}</span>
                            @endif
                        </a>
                    </li>

                    {{-- Financials Section --}}
                    <li class="mt-6 mb-2">
                        <span class="text-[0.65rem] font-bold uppercase tracking-widest text-brand-400/50 px-4">
                            Financials
                        </span>
                    </li>
                    <li>
                        <a href="{{ route('payments.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                            {{ request()->routeIs('payments.*')
    ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/50'
    : 'text-brand-200 hover:bg-brand-900/50 hover:text-white' }}">
                            <span
                                class="icon-[tabler--credit-card] size-5 {{ request()->routeIs('payments.*') ? '' : 'group-hover:scale-110' }} transition-transform"></span>
                            <span class="font-medium">Payments</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('expenses.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                            {{ request()->routeIs('expenses.*')
    ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/50'
    : 'text-brand-200 hover:bg-brand-900/50 hover:text-white' }}">
                            <span
                                class="icon-[tabler--receipt] size-5 {{ request()->routeIs('expenses.*') ? '' : 'group-hover:scale-110' }} transition-transform"></span>
                            <span class="font-medium">Expenses</span>
                        </a>
                    </li>
                    </li>

                    {{-- System Section --}}
                    <li class="mt-6 mb-2">
                        <span class="text-[0.65rem] font-bold uppercase tracking-widest text-brand-400/50 px-4">
                            System
                        </span>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                            {{ request()->routeIs('users.*')
    ? 'bg-brand-600 text-white shadow-lg shadow-brand-900/50'
    : 'text-brand-200 hover:bg-brand-900/50 hover:text-white' }}">
                            <span
                                class="icon-[tabler--user-cog] size-5 {{ request()->routeIs('users.*') ? '' : 'group-hover:scale-110' }} transition-transform"></span>
                            <span class="font-medium">Users</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Quick Stats --}}
            <div class="px-4 py-3 border-t border-brand-800/50">
                <div class="grid grid-cols-2 gap-2">
                    <div class="bg-brand-900/50 rounded-lg p-3 text-center">
                        <div class="text-lg font-bold text-brand-400">{{ $todaySales ?? '₵0' }}</div>
                        <div class="text-[0.65rem] text-brand-300/50 uppercase tracking-wide">Today Sales</div>
                    </div>
                    <div class="bg-brand-900/50 rounded-lg p-3 text-center">
                        <div class="text-lg font-bold text-green-400">{{ $pendingPayments ?? 0 }}</div>
                        <div class="text-[0.65rem] text-brand-300/50 uppercase tracking-wide">Pending</div>
                    </div>
                </div>
            </div>
        </div>
    </aside>
</div>
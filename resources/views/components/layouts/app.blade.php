<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @notifyCss
</head>

<body class="font-sans antialiased text-base-content bg-gray-100" x-data="{}">
    <div class="flex h-screen overflow-hidden bg-gray-100">
        <!-- Sidebar -->
        @include('components.layouts.sidebar')

        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            <!-- Navbar -->
            @include('components.layouts.topbar', ['header' => $header ?? null])

            <!-- Main Content -->
            <main class="w-full grow p-6 pb-24 lg:pb-6 relative">
                <x-notify::notify />
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Bottom Navigation (Mobile Only) -->
    @include('components.layouts.bottom-nav')

    @stack('modals')
    @stack('scripts')
    @livewireScripts
    @notifyJs
    <script>
        // FilePond plugin registration is handled in app.js
    </script>
</body>

</html>
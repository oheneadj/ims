@props([
    'sidebar' => false,
])

<a {{ $attributes }} class="flex items-center gap-2">
    <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-neutral text-neutral-content">
        <x-app-logo-icon class="size-5 fill-current text-white" />
    </div>
    <span class="font-bold text-lg">{{ config('app.name') }}</span>
</a>

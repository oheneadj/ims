@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center gap-2">
    <h1 class="text-3xl font-bold">{{ $title }}</h1>
    <p class="text-sm opacity-70">{{ $description }}</p>
</div>

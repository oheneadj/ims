@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 w-full">
        {{-- Results Info --}}
        <div class="text-sm opacity-70">
            Showing
            <span class="font-semibold">{{ $paginator->firstItem() }}</span>
            to
            <span class="font-semibold">{{ $paginator->lastItem() }}</span>
            of
            <span class="font-semibold">{{ $paginator->total() }}</span>
            results
        </div>

        {{-- Navigation --}}
        <nav class="flex items-center gap-x-1" role="navigation" aria-label="Pagination Navigation">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button type="button" class="btn btn-soft max-sm:btn-square" disabled aria-disabled="true">
                    <span class="icon-[tabler--chevron-left] size-5 rtl:rotate-180 sm:hidden"></span>
                    <span class="hidden sm:inline">Previous</span>
                </button>
            @else
                <button type="button" wire:click="previousPage" class="btn btn-soft max-sm:btn-square">
                    <span class="icon-[tabler--chevron-left] size-5 rtl:rotate-180 sm:hidden"></span>
                    <span class="hidden sm:inline">Previous</span>
                </button>
            @endif

            {{-- Pagination Elements --}}
            <div class="flex items-center gap-x-1">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <div class="tooltip inline-block">
                            <button type="button" class="tooltip-toggle btn btn-soft btn-square group" aria-label="More Pages">
                                <span class="icon-[tabler--dots] size-5 group-hover:hidden"></span>
                                <span
                                    class="icon-[tabler--chevrons-right] rtl:rotate-180 hidden size-5 shrink-0 group-hover:block"></span>
                                <span class="tooltip-content tooltip-shown:opacity-100 tooltip-shown:visible" role="tooltip">
                                    <span class="tooltip-body">More pages</span>
                                </span>
                            </button>
                        </div>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <button type="button" class="btn btn-soft btn-square btn-primary" aria-current="page">
                                    {{ $page }}
                                </button>
                            @else
                                <button type="button" wire:click="gotoPage({{ $page }})" class="btn btn-soft btn-square">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <button type="button" wire:click="nextPage" class="btn btn-soft max-sm:btn-square">
                    <span class="hidden sm:inline">Next</span>
                    <span class="icon-[tabler--chevron-right] size-5 rtl:rotate-180 sm:hidden"></span>
                </button>
            @else
                <button type="button" class="btn btn-soft max-sm:btn-square" disabled aria-disabled="true">
                    <span class="hidden sm:inline">Next</span>
                    <span class="icon-[tabler--chevron-right] size-5 rtl:rotate-180 sm:hidden"></span>
                </button>
            @endif
        </nav>
    </div>
@endif
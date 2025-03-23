@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center">
        {{-- Mobile Navigation --}}
        <div class="flex justify-center flex-1 sm:hidden gap-1.25">
            {{-- Previous Page Link Mobile --}}
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-neutral-700/50 border border-neutral-600 cursor-not-allowed leading-5 rounded-md">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-emerald-500 bg-neutral-700 border border-neutral-600 leading-5 rounded-md hover:text-emerald-400">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            {{-- Next Page Link Mobile --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-emerald-500 bg-neutral-700 border border-neutral-600 leading-5 rounded-md hover:text-emerald-400">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 bg-neutral-700/50 border border-neutral-600 cursor-not-allowed leading-5 rounded-md">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- Desktop Navigation --}}
        <div class="hidden sm:flex sm:items-center sm:justify-between">
            <div>
                <span class="relative z-0 inline-flex rounded-md gap-2">
                    {{-- Previous Arrow --}}
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-neutral-700/50 border border-neutral-600 cursor-not-allowed leading-5 rounded-md">
                            &laquo;
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-emerald-500 bg-neutral-700 border border-neutral-600 leading-5 rounded-md hover:text-emerald-400">
                            &laquo;
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-neutral-700/50 border border-neutral-600 cursor-default leading-5 rounded-md">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @php
                                    $isFirstPage = $page == 1;
                                    $isLastPage = $page == $paginator->lastPage();
                                    $isWithinOnePages = abs($paginator->currentPage() - $page) <= 1;
                                    $shouldShowDots = !$isWithinOnePages && !$isFirstPage && !$isLastPage;
                                @endphp

                                @if ($isFirstPage || $isLastPage || $isWithinOnePages)
                                    @if ($page == $paginator->currentPage())
                                        <span aria-current="page" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-emerald-500 border border-emerald-500 cursor-default leading-5 rounded-md">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-emerald-500 bg-neutral-700 border border-neutral-600 leading-5 hover:text-emerald-400 focus:z-10 focus:outline-none focus:ring ring-emerald-300 focus:border-emerald-300 active:bg-neutral-600 transition ease-in-out duration-150 rounded-md">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @elseif ($shouldShowDots && $page == $paginator->currentPage() - 2 || $page == $paginator->currentPage() + 2)
                                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-neutral-700/50 border border-neutral-600 cursor-default leading-5 rounded-md">
                                        ...
                                    </span>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Arrow --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-emerald-500 bg-neutral-700 border border-neutral-600 leading-5 rounded-md hover:text-emerald-400">
                            &raquo;
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-neutral-700/50 border border-neutral-600 cursor-not-allowed leading-5 rounded-md">
                            &raquo;
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif

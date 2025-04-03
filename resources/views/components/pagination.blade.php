@if ($paginator->hasPages())


    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-6">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <span class="px-4 py-2 bg-white text-gray-400 cursor-not-allowed border border-gray-300 rounded">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 prev-page" data-page="{{ $paginator->currentPage() - 1 }}">
                Previous
            </a>
        @endif
        {{-- Pagination Elements --}}
        
        @foreach ($paginator->links()->elements as $element)
            @if (is_string($element))
                <span class="px-4 py-2 text-gray-400">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class=" mx-2 px-4 py-2 bg-primary text-white rounded">{{ $page }}</span>
                     @else
                        <a href="{{ $url }}" class=" mx-2 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 page-link" data-page="{{ $page }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 next-page" data-page="{{ $paginator->currentPage() + 1 }}">
                Next
            </a>
        @else
        <span class="px-4 py-2 bg-white text-gray-400 cursor-not-allowed border border-gray-300 rounded">Next</span>
        @endif
    </nav>
@endif
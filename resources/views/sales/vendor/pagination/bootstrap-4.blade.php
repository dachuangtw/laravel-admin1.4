<ul class="pagination flex-m flex-w p-r-50">
	{{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
		<li class="item-pagination flex-c-m trans-0-4"><span class="ti-angle-double-left"></span></li>
    @else
		<a href="{{ $paginator->previousPageUrl() }}" rel="prev"><li class="item-pagination flex-c-m trans-0-4"><span class="ti-angle-double-left"></span></li></a>
    @endif

	{{-- Pagination Elements --}}
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
			<li class="item-pagination flex-c-m trans-0-4 active-pagination">{{ $element }}</li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
					<li class="item-pagination flex-c-m trans-0-4 active-pagination">{{ $page }}</li>
                @else
					<a href="{{ $url }}"><li class="item-pagination flex-c-m trans-0-4">{{ $page }}</li></a>
                @endif
            @endforeach
        @endif
    @endforeach

	{{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
		<a href="{{ $paginator->nextPageUrl() }}" rel="next"><li class="item-pagination flex-c-m trans-0-4"><span class="ti-angle-double-right"></span></li></a>
    @else
		<li class="item-pagination flex-c-m trans-0-4"><span class="ti-angle-double-right"></span></li>
    @endif
</ul>

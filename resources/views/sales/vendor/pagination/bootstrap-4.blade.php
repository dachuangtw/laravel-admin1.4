<ul class="pagination flex-m flex-w p-r-50">
	{{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
		<li class="item-pagination flex-c-m trans-0-4"><span class="ti-angle-double-left"></span></li>
    @else
		<li class="item-pagination flex-c-m trans-0-4"><a href="{{ $paginator->previousPageUrl() }}" rel="prev"><span class="ti-angle-double-left"></span></a></li>
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
					<li class="item-pagination flex-c-m trans-0-4"><a href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
    @endforeach

	{{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
		<li class="item-pagination flex-c-m trans-0-4"><a href="{{ $paginator->nextPageUrl() }}" rel="next"><span class="ti-angle-double-right"></span></a></li>
    @else
		<li class="item-pagination flex-c-m trans-0-4"><span class="ti-angle-double-right"></span></li>
    @endif
</ul>

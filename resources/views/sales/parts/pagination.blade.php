@if ($paginator->hasPages())
<div class="flex-sb-m flex-w p-b-35">
	<span class="s-text8 p-t-5 p-b-5">
		顯示 {{ $paginator->total() }} 筆中的 {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} 筆
	</span>
	{{ $paginator->links('sales.vendor.pagination.bootstrap-4') }}
</div>
@endif

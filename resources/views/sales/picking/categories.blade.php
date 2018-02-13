<h4 class="m-text14 p-b-7">
	商品分類
</h4>

<ul class="p-b-54">
	@if( !isset($category_id))
	{{-- 全部商品 --}}
		@component('sales.product.component.categories', ['active' => 1])
			全部
		@endcomponent

		@foreach ($categories as $category)
			@component('sales.product.component.categories', ['active' => 0, 'url' => url('picking/'.$category->pcid)])
				{{ $category->pc_name }}
			@endcomponent
		@endforeach

		@component('sales.product.component.categories', ['active' => 0, 'url' => url('picking/0')])
			未分類
		@endcomponent

	@else
	{{-- 商品分類 --}}
		@component('sales.product.component.categories', ['active' => 0, 'url' => url('picking')])
			全部
		@endcomponent

		@foreach ($categories as $category)
			@component('sales.product.component.categories', ['active' => ($category_id == $category->pcid), 'url' => url('picking/'.$category->pcid)])
				{{ $category->pc_name }}
			@endcomponent
		@endforeach

		@component('sales.product.component.categories', ['active' => (!$category_id), 'url' => url('picking/0')])
			未分類
		@endcomponent
	@endif
</ul>

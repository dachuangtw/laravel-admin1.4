<h4 class="m-text14 p-b-7">
	商品分類
</h4>

<ul class="p-b-54">
	<li class="p-t-4">
		@if($category_id === NULL)
		<span class="s-text13 active1">
			<span class="ti-angle-right"></span>
			全部
		</span>
		@else
		<a href="{{ url('product') }}" class="s-text13">
			全部
		</a>
		@endif
	</li>

	@foreach ($categories as $category)
	<li class="p-t-4">
		<a href="{{ url('product/'.$category->pcid) }}" class="s-text13{{ $category_id == $category->pcid ? ' active1' : ''}}">
			@if($category_id == $category->pcid)
			<span class="ti-angle-right"></span>
			@endif
			{{ $category->pc_name }}
		</a>
	</li>
	@endforeach

	<li class="p-t-4">
		@if($category_id === 0)
		<span class="s-text13 active1">
			<span class="ti-angle-right"></span>
			未分類
		</span>
		@else
		<a href="{{ url('product/0') }}" class="s-text13">
			未分類
		</a>
		@endif
	</li>
</ul>

<h4 class="m-text14 p-b-7">
	商品分類
</h4>

<ul class="p-b-54">
	<li class="p-t-4">
		@if(isset($categories_id))
		<a href="{{ url('product') }}" class="s-text13">
			全部
		</a>
		@else
		<span class="s-text13 active1">
			<span class="ti-angle-right"></span>
			全部
		</span>
		@endif
	</li>

	@foreach ($categories as $category)
	<li class="p-t-4">
		@if(isset($categories_id))
		<a href="{{ url('product/'.$category->pcid) }}" class="s-text13{{ isset($categories_id) && $categories_id == $category->pcid ? ' active1' : ''}}">
			@if($categories_id == $category->pcid)
			<span class="ti-angle-right"></span>
			@endif
			{{ $category->pc_name }}
		</a>
		@else
		<a href="{{ url('product/'.$category->pcid) }}" class="s-text13">
			{{ $category->pc_name }}
		</a>
		@endif
	</li>
	@endforeach

	<li class="p-t-4">
		@if(isset($categories_id) && $categories_id == 0)
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

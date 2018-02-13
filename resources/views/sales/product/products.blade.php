@foreach($products as $product)
<div class="col-sm-12 col-md-6 col-lg-4 p-b-50">
	<!-- Block2 -->
	<div class="block2">
		<div class="block2-img wrap-pic-w of-hidden pos-relative{{ $product->shownew ? ' block2-labelnew' : ' block2' }}">
			@if($product->p_pic)
			<img src="{{ asset('upload/' . $product->p_pic) }}" alt="IMG-PRODUCT">
			@else
			<img src="{{ asset(config('sales.asset_path') . 'images/item-02.jpg') }}" alt="IMG-PRODUCT">
			@endif
		</div>

		<div class="block2-txt p-t-20">
			<a href="{{ url('/product/detail/'.$product->p_number)}}" class="block2-name dis-block s-text3 p-b-5">
				{{ $product->p_name }}
			</a>

			<span class="block2-price m-text6 p-r-5">
				${{ $product->p_salesprice }}
			</span>
		</div>
	</div>
</div>
@endforeach

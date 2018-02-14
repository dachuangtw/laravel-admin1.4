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

			<!-- <div class="block2-overlay trans-0-4">
				<div class="block2-btn-addcart w-size1 trans-0-4">
					<button class="flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4">
						加到領貨
					</button>
				</div>
			</div> -->
		</div>

		<div class="block2-txt p-t-20">
			<a href="{{ url('/picking/detail/'.$product->p_number)}}" class="block2-name dis-block m-text6 p-b-5">
				{{ $product->p_name }}
			</a>
			<span class="block2-price s-text3 p-r-5">
				${{ $product->p_salesprice }}
			</span>
			<!-- <br/>
			編號:
			<span class="block2-number s-text3 p-r-5">
				{{ $product->p_number }}
			</span> -->
		</div>
	</div>
</div>
@endforeach

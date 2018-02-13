@extends('sales.layouts.master')

@section('content')
<!-- breadcrumb -->
@includeIf('sales.parts.breadcrumb-back')

<!-- Product Detail -->
<div class="container bgwhite p-t-35 p-b-80">
	<div class="flex-w flex-sb">

		{{-- 圖片顯示 --}}
		<div class="w-size13 p-t-30 respon5">
			<div class="wrap-slick3 flex-sb flex-w">
				<div class="wrap-slick3-dots"></div>

				<div class="slick3">
					@if($product->p_pic)
					<div class="item-slick3" data-thumb="{{ asset('upload/' . $product->p_pic) }}">
						<div class="wrap-pic-w">
							<img src="{{ asset('upload/' . $product->p_pic) }}" alt="IMG-PRODUCT">
						</div>
					</div>
					@else
					<div class="item-slick3" data-thumb="{{ asset(config('sales.asset_path') . 'images/thumb-item-01.jpg') }}">
						<div class="wrap-pic-w">
							<img src="{{ asset(config('sales.asset_path') . 'images/product-detail-01.jpg') }}" alt="IMG-PRODUCT">
						</div>
					</div>
					@endif

					@if($product->p_images)
						@foreach($product->p_images as $image)
						<div class="item-slick3" data-thumb="{{ asset('upload/' . $image) }}">
							<div class="wrap-pic-w">
								<img src="{{ asset('upload/' . $image) }}" alt="IMG-PRODUCT">
							</div>
						</div>
						@endforeach
					@endif
				</div>
			</div>
		</div>

		<div class="w-size14 p-t-30 respon5">
			<h4 class="product-detail-name m-text16 p-b-13">
				{{ $product->p_name }}
			</h4>

			<span class="m-text17">
				${{ $product->p_salesprice }}
			</span>

			<p class="s-text8 p-t-10">
				商品編號: {{ $product->p_number }}

			</p>

			<div class="p-b-45">
				<span class="s-text8 m-r-35">庫存數量: {{ (! empty($product->hasManyStock)) ? $product->hasManyStock->sum('st_stock') : '0' }}</span>
				<span class="s-text8">可領貨數: {{ (! empty($product->hasManyStock)) ? $product->hasManyStock->sum('st_collect') : '0' }}</span>
			</div>

			<div class="wrap-dropdown-content bo6 p-t-15 p-b-14 active-dropdown-content">
				<h5 class="js-toggle-dropdown-content flex-sb-m cs-pointer m-text19 color0-hov trans-0-4">
					商品說明
					<i class="down-mark fs-12 color1 fa fa-minus dis-none" aria-hidden="true"></i>
					<i class="up-mark fs-12 color1 fa fa-plus" aria-hidden="true"></i>
				</h5>

				<div class="dropdown-content dis-none p-t-15 p-b-23">
					<p class="s-text8">
						{{ $product->p_description }}
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/slick/slick.min.js') }}"></script>
<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'js/slick-custom.js') }}"></script>
@endsection

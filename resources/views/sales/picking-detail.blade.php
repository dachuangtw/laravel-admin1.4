@extends('sales.layouts.master')

@section('picking-bag')
	@include('sales.header.shopping')
@endsection

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
							<img id="product-detail-pic" src="{{ asset('upload/' . $product->p_pic) }}" alt="IMG-PRODUCT">
						</div>
					</div>
					@else
					<div class="item-slick3" data-thumb="{{ asset(config('sales.asset_path') . 'images/thumb-item-01.jpg') }}">
						<div class="wrap-pic-w">
							<img id="product-detail-pic" src="{{ asset(config('sales.asset_path') . 'images/product-detail-01.jpg') }}" alt="IMG-PRODUCT">
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

			$<span class="product-detail-price m-text17">
				{{ $product->p_salesprice }}
			</span>

			<p class="s-text8 p-t-10">
				商品編號:
				<span class="product-detail-number">
					{{ $product->p_number }}
				</span>
			</p>

			<div class="p-b-45">
				<span class="s-text8 m-r-35">庫存數量: {{ ($product->hasManyStock->count()) ? $product->hasManyStock->sum('st_stock') : '0' }}</span>
				<span class="s-text8">可領貨數: {{ ($product->hasManyStock->count()) ? $product->hasManyStock->sum('st_collect') : '0' }}</span>
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

			<div class="p-t-33 p-b-60">
				@if($product->hasManyStock->count())
				<div class="flex-m flex-w">
					<div class="s-text15 w-size15 t-center">
						款式
					</div>

					<div class="rs2-select2 rs3-select2 bo4 of-hidden w-size16">
						<select class="selection-2" id="product_type" name="color">
							@foreach($product->hasManyStock as $product_stock)
							<option>{{ $product_stock->st_type }}</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="flex-r-m flex-w p-t-10">
					<div class="w-size16 flex-m flex-w">
						<div class="flex-w bo5 of-hidden m-r-22 m-t-10 m-b-10">
							<button class="btn-num-product-down color1 flex-c-m size7 bg8 eff2">
								<i class="fs-12 fa fa-minus" aria-hidden="true"></i>
							</button>

							<input class="size8 m-text18 t-center num-product" type="number" name="num-product" value="1">

							<button class="btn-num-product-up color1 flex-c-m size7 bg8 eff2">
								<i class="fs-12 fa fa-plus" aria-hidden="true"></i>
							</button>
						</div>

						<div class="btn-addcart-product-detail size9 trans-0-4 m-t-10 m-b-10">
							<!-- Button -->
							<button class="flex-c-m sizefull bg1 bo-rad-23 hov1 s-text1 trans-0-4">
								加到領貨
							</button>
						</div>
					</div>
				</div>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/slick/slick.min.js') }}"></script>
<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'js/slick-custom.js') }}"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/countdowntime/countdowntime.js') }}"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/sweetalert/sweetalert.min.js') }}"></script>
<script type="text/javascript">
	$.ajaxSetup({
	  headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});

	$('.btn-addcart-product-detail').on('click', function(){
		var nameProduct = $('.product-detail-name').text();
		var numberProduct = $('.product-detail-number').text();
		var qtyProduct = $('.num-product').val();
		var typeProduct = $('#product_type').val();

		// 加入領貨
		$.ajax({
			url: "{{ url('cart/add') }}",
			type:"POST",
			data: {
				id: numberProduct,
				qty: qtyProduct
			},
			success:function(data){
				$('.header-icons-noti').text(data.cart_content_count);
				console.log(data);
				swal(nameProduct+' '+typeProduct+'(數量:'+qtyProduct+')', '成功增加到領貨!', "success");
			},error:function(){
				swal({
					title: '發生錯誤!',
					icon: "warning"
				});
			}
		});
	});
</script>
@endsection

@extends('sales.layouts.master')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'vendor/noui/nouislider.min.css') }}">
@endsection

@section('content')
<section class="bgwhite p-t-55 p-b-65">
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-md-4 col-lg-3 p-b-50">
				<div class="leftbar p-r-20 p-r-0-sm">
					<!-- leftbar -->
					@includeIf('sales.product.categories')

				</div>
			</div>

			<div class="col-sm-6 col-md-8 col-lg-9 p-b-50">
				<!--  -->
				@includeIf('sales.product.sort')

				<!-- Product -->
				<div class="row">
					@for ($i = 0; $i < 12; $i++)
						@includeIf('sales.product.products')
					@endfor
				</div>

				<!-- Pagination -->
				<div class="pagination flex-m flex-w p-t-26">
					<a href="#" class="item-pagination flex-c-m trans-0-4 active-pagination">1</a>
					<a href="#" class="item-pagination flex-c-m trans-0-4">2</a>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/daterangepicker/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/daterangepicker/daterangepicker.js') }}"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/slick/slick.min.js') }}"></script>
<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'js/slick-custom.js') }}"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/sweetalert/sweetalert.min.js') }}"></script>

<script type="text/javascript">
	$('.block2-btn-addcart').each(function(){
		var nameProduct = $(this).parent().parent().parent().find('.block2-name').html();
		$(this).on('click', function(){
			swal(nameProduct, "is added to cart !", "success");
		});
	});

	$('.block2-btn-addwishlist').each(function(){
		var nameProduct = $(this).parent().parent().parent().find('.block2-name').html();
		$(this).on('click', function(){
			swal(nameProduct, "is added to wishlist !", "success");
		});
	});
</script>
@endsection

@extends('sales.layouts.master')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'vendor/noui/nouislider.min.css') }}">
@endsection

@section('picking-bag')
	@if($picking)
	@include('sales.parts.header-shopping')
	@endif
@endsection

@section('content')
<section class="bgwhite p-t-55 p-b-65">
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-md-4 col-lg-3 p-b-50">
				<div class="leftbar p-r-20 p-r-0-sm">
					<!-- leftbar -->
					@include('sales.product.picking-time')

					@include('sales.product.categories')

				</div>
			</div>

			<div class="col-sm-6 col-md-8 col-lg-9 p-b-50">

				@include('sales.parts.pagination', ['paginator' => $products])

				<!-- Product -->
				<div class="row">
					@include('sales.product.products')
				</div>

				@include('sales.parts.pagination', ['paginator' => $products])

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
<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/countdowntime/countdowntime.js') }}"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/lightbox2/js/lightbox.min.js') }}"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/sweetalert/sweetalert.min.js') }}"></script>

<script type="text/javascript">
	$('.block2-btn-addcart').each(function(){
		var nameProduct = $(this).parent().parent().parent().find('.block2-name').html();
		$(this).on('click', function(){
            // 加入領貨
			$.post('', { name: "John", time: "2pm" },
			function(response){
			alert("Data Loaded: " + response);
			});

			swal(nameProduct, "已經加到領貨", "success");
		});
	});
</script>
@endsection

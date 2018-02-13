@extends('sales.layouts.master')

@section('picking-bag')
	@include('sales.header.shopping')
@endsection

@section('content')
	@component('sales.product.component.content')
		@slot('leftbar')
			@include('sales.picking.categories')
		@endslot

		@include('sales.parts.pagination', ['paginator' => $products])

		<!-- Product -->
		<div class="row">
			@include('sales.picking.products')
		</div>

		@include('sales.parts.pagination', ['paginator' => $products])

	@endcomponent
@endsection

@section('script')
<!-- <script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/daterangepicker/moment.min.js') }}"></script> -->
<!-- <script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/daterangepicker/daterangepicker.js') }}"></script> -->
<!--===============================================================================================-->
<!-- <script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/slick/slick.min.js') }}"></script> -->
<!-- <script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'js/slick-custom.js') }}"></script> -->
<!--===============================================================================================-->
<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/countdowntime/countdowntime.js') }}"></script>
<!--===============================================================================================-->
<!-- <script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/lightbox2/js/lightbox.min.js') }}"></script> -->
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

	$
</script>
@endsection

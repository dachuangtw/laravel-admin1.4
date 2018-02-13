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

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
	$('.block2-btn-addcart').each(function(){
		var nameProduct = $(this).parent().parent().parent().find('.block2-name').html();
		var numberProduct = $(this).parent().parent().parent().find('.block2-number').html();
		var priceProduct = $(this).parent().parent().parent().find('.block2-price').html();
		$(this).on('click', function(){
            // 加入領貨
			$.ajax({
				url: "{{ url('cart/add') }}",
				type:"POST",
				beforeSend: function (xhr) {
					var token = $('meta[name="csrf_token"]').attr('content');

					if (token) {
						return xhr.setRequestHeader('X-CSRF-TOKEN', token);
					}
				},
				data: {
					id: numberProduct,
					name: nameProduct,
					price: priceProduct
				},
				success:function(data){
					alert(data);
				},error:function(){
					alert("error!!!!");
				}
			});
		});
	});
</script>
@endsection

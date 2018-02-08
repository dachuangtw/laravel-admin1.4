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
					<h4 class="m-text14 p-b-7">
						領貨剩餘時間
					</h4>
					@if($picking)
					<div class="flex-c-m p-t-4 p-b-54">
						<div class="flex-col-c-m size3 bo1 m-l-5 m-r-5">
							<span class="m-text10 p-b-1 days">
								{{ $days }}
							</span>

							<span class="s-text5">
								日
							</span>
						</div>

						<div class="flex-col-c-m size3 bo1 m-l-5 m-r-5">
							<span class="m-text10 p-b-1 hours">
								{{ $hours }}
							</span>

							<span class="s-text5">
								時
							</span>
						</div>

						<div class="flex-col-c-m size3 bo1 m-l-5 m-r-5">
							<span class="m-text10 p-b-1 minutes">
								{{ $minutes }}
							</span>

							<span class="s-text5">
								分
							</span>
						</div>

						<div class="flex-col-c-m size3 bo1 m-l-5 m-r-5">
							<span class="m-text10 p-b-1 seconds">
								{{ $seconds }}
							</span>

							<span class="s-text5">
								秒
							</span>
						</div>
					</div>
					@else
					<div class="p-b-54 p-t-4 s-text13">
						目前並非領貨時間
					</div>
					@endif

					@include('sales.product.categories')

				</div>
			</div>

			<div class="col-sm-6 col-md-8 col-lg-9 p-b-50">
				<!--  -->
				@includeIf('sales.product.sort')

				<!-- Product -->
				<div class="row">
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

								<div class="block2-overlay trans-0-4">
									<div class="block2-btn-addcart w-size1 trans-0-4">
										<!-- Button -->
										@if($picking)
										<button class="flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4">
											加到領貨
										</button>
										@endif
									</div>
								</div>
							</div>

							<div class="block2-txt p-t-20">
								<a href="{{ url('/product-detail')}}" class="block2-name dis-block s-text3 p-b-5">
									{{ $product->p_name }}
								</a>

								<span class="block2-price m-text6 p-r-5">
									${{ $product->p_salesprice }}
								</span>
							</div>
						</div>
					</div>
					@endforeach
				</div>

				<!-- Pagination -->
				<!-- <div class="pagination flex-m flex-w p-t-26">
					<a href="#" class="item-pagination flex-c-m trans-0-4 active-pagination">1</a>
					<a href="#" class="item-pagination flex-c-m trans-0-4">2</a>
				</div> -->
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
			swal(nameProduct, "已經加到領貨", "success");
		});
	});
</script>
@endsection

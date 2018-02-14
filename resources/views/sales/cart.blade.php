@extends('sales.layouts.master')

@section('picking-bag')
	@include('sales.header.shopping')
@endsection

@section('content')
<section class="bgwhite p-t-70 p-b-100">
	<div class="container">
		<div class="min-h-550">
			<h4 class="m-text24 p-b-11">領貨內容</h4>
			<div class="container-table-cart pos-relative">
				<div class="wrap-table-text bgwhite">
					<table class="table-text">
						<tr class="table-head">
							<th class="column">商品</th>
							<th class="column">價格</th>
							<th class="column">數量</th>
						</tr>

						@foreach($cart as $cart_item)
						<tr class="table-row">
							<td class="column">{{ $cart_item->name }}</td>
							<td class="column">${{ $cart_item->price }}</td>
							<td class="column">
								<!-- <div class="flex-w bo5 of-hidden w-size17">
									<button class="btn-num-product-down color1 flex-c-m size7 bg8 eff2">
										<i class="fs-12 fa fa-minus" aria-hidden="true"></i>
									</button>

									<input class="size8 m-text18 t-center num-product" type="number" name="num-product1" value="1">

									<button class="btn-num-product-up color1 flex-c-m size7 bg8 eff2">
										<i class="fs-12 fa fa-plus" aria-hidden="true"></i>
									</button>
								</div> -->
								{{ $cart_item->qty }}
							</td>
						</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

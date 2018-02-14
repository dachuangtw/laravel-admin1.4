@extends('sales.layouts.master')

@section('picking-bag')
	@include('sales.header.shopping')
@endsection

@section('content')
<section class="bgwhite p-t-70 p-b-100">
	<div class="container">
		<div class="min-h-550">
			<h5 class="m-text20">
				領貨明細
			</h5>
			<div class="container-table-cart pos-relative">
				<div class="wrap-table-text bgwhite">
					<table class="table-text">
						<tr class="table-head">
							<th class="column-1"></th>
							<th class="column">商品</th>
							<th class="column">價格</th>
							<th class="column p-l-70">數量</th>
							<th class="column">總和</th>
						</tr>

						@foreach($cart_content as $cart_item)
						<tr class="table-row">
							<td class="column">
								<a href="{{ url('picking/detail/'.$cart_item->id)}}">
									<div class="cart-img-product b-rad-4 o-f-hidden">
										<img src="{{ $cart_item->options->pic }}" alt="IMG-PRODUCT">
									</div>
								</a>
								<span class="id-product">{{ $cart_item->id }}</span>
							</td>
							<td class="column">
								<a href="{{ url('picking/detail/'.$cart_item->id)}}">{{ $cart_item->name }}</a>
							</td>
							<td class="column">
								${{ $cart_item->price }}
							</td>
							<td class="column">
								{{ $cart_item->qty }}
							</td>
							<td class="column">
								${{ $cart_item->price * $cart_item->qty }}
							</td>
						</tr>
						@endforeach
					</table>
				</div>

			</div>

			<div class="bo9 w-size18 p-l-40 p-r-40 p-t-30 p-b-38 m-t-30 m-r-0 m-l-auto p-lr-15-sm">
				<h5 class="m-text20 p-b-24">
					領貨單總計
				</h5>

				<div class="flex-w flex-sb-m p-b-12">
					<span class="s-text18 w-size19">
						商品種類:
					</span>

					<span class="m-text21 w-size20">
						{{ $cart_content->count() }}
					</span>
				</div>

				<div class="flex-w flex-sb-m bo10 p-t-15 p-b-20">
					<span class="s-text18 w-size19">
						商品個數:
					</span>

					<span class="m-text21 w-size20">
						{{ $cart_count }}
					</span>
				</div>


				<div class="flex-w flex-sb-m p-t-26 p-b-30">
					<span class="m-text22 w-size19">
						總合計:
					</span>

					<span class="m-text21 w-size20">
						${{ $cart_subtotal }}
					</span>
				</div>

				<div class="size15 trans-0-4">
					<!-- Button -->
					<button class="flex-c-m sizefull bg1 bo-rad-23 hov1 s-text1 trans-0-4">
						完成領貨
					</button>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/countdowntime/countdowntime.js') }}"></script>
@endsection

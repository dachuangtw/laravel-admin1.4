<span class="linedivide1"></span>

<div class="header-wrapicon2">
	<img src="{{ asset(config('sales.asset_path') . 'images/icons/icon-header-02.png') }}" class="header-icon1 js-show-header-dropdown" alt="ICON">
	<span class="header-icons-noti">3</span>

	<!-- Header cart noti -->
	<div class="header-cart header-dropdown">

		<h4 class="m-text14 p-b-7">
			領貨剩餘時間
		</h4>
		<div class="flex-c-m p-t-4 p-b-54">
			<div class="flex-col-c-m size3 bo1 m-l-5 m-r-5">
				<span class="m-text10 p-b-1 days">
					{{ $picking_time['d'] }}
				</span>

				<span class="s-text5">
					日
				</span>
			</div>

			<div class="flex-col-c-m size3 bo1 m-l-5 m-r-5">
				<span class="m-text10 p-b-1 hours">
					{{ $picking_time['H'] }}
				</span>

				<span class="s-text5">
					時
				</span>
			</div>

			<div class="flex-col-c-m size3 bo1 m-l-5 m-r-5">
				<span class="m-text10 p-b-1 minutes">
					{{ $picking_time['i'] }}
				</span>

				<span class="s-text5">
					分
				</span>
			</div>

			<div class="flex-col-c-m size3 bo1 m-l-5 m-r-5">
				<span class="m-text10 p-b-1 seconds">
					{{ $picking_time['s'] }}
				</span>

				<span class="s-text5">
					秒
				</span>
			</div>
		</div>


		<ul class="header-cart-wrapitem">
			<!-- <li class="header-cart-item">
				<div class="header-cart-item-img">
					<img src="{{ asset(config('sales.asset_path') . 'images/item-cart-01.jpg') }}" alt="IMG">
				</div>

				<div class="header-cart-item-txt">
					<a href="#" class="header-cart-item-name">
						商品V
					</a>

					<span class="header-cart-item-info">
						1 x $19
					</span>
				</div>
			</li> -->

			<!-- <li class="header-cart-item">
				<div class="header-cart-item-img">
					<img src="{{ asset(config('sales.asset_path') . 'images/item-cart-02.jpg') }}" alt="IMG">
				</div>

				<div class="header-cart-item-txt">
					<a href="#" class="header-cart-item-name">
						商品R
					</a>

					<span class="header-cart-item-info">
						1 x $39
					</span>
				</div>
			</li> -->

			<!-- <li class="header-cart-item">
				<div class="header-cart-item-img">
					<img src="{{ asset(config('sales.asset_path') . 'images/item-cart-03.jpg') }}" alt="IMG">
				</div>

				<div class="header-cart-item-txt">
					<a href="#" class="header-cart-item-name">
						商品Y
					</a>

					<span class="header-cart-item-info">
						1 x $17
					</span>
				</div>
			</li> -->
		</ul>

		<div class="header-cart-total">
			總計: $75
		</div>

		<div class="header-cart-buttons">
			<div class="header-cart-wrapbtn">
				<!-- Button -->
				<a href="{{ url('/cart') }}" class="flex-c-m size1 bg1 bo-rad-20 hov1 s-text1 trans-0-4">
					查看
				</a>
			</div>

			<div class="header-cart-wrapbtn">
				<!-- Button -->
				<a href="#" class="flex-c-m size1 bg1 bo-rad-20 hov1 s-text1 trans-0-4">
					完成
				</a>
			</div>
		</div>
	</div>
</div>

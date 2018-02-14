<span class="linedivide1"></span>

<div class="header-wrapicon2">
	<img src="{{ asset(config('sales.asset_path') . 'images/icons/icon-header-02.png') }}" class="header-icon1 js-show-header-dropdown" alt="ICON">
	<span class="header-icons-noti">{{ $cart_content->count() }}</span>

	<!-- Header cart noti -->
	<div class="header-cart header-dropdown">

		@include('sales.picking.limited-time')

		<!-- <ul class="header-cart-wrapitem">
			<li class="header-cart-item">
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
			</li>

			<li class="header-cart-item">
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
			</li>

			<li class="header-cart-item">
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
			</li>
		</ul>

		<div class="header-cart-total">
			總計: $75
		</div> -->

		<div class="header-cart-buttons">
			<div class="header-cart-wrapbtn">
				<!-- Button -->
				<a href="{{ url('/cart') }}" class="flex-c-m size1 bg1 bo-rad-20 hov1 s-text1 trans-0-4">
					查看領貨
				</a>
			</div>

			<div class="header-cart-wrapbtn">
				<!-- Button -->
				<a href="#" class="flex-c-m size1 bg1 bo-rad-20 hov1 s-text1 trans-0-4">
					完成領貨
				</a>
			</div>
		</div>
	</div>
</div>

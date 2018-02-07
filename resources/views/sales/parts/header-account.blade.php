<div class="header-wrapicon2">
	<img src="{{ asset(config('sales.asset_path') . 'images/icons/icon-header-01.png') }}" class="header-icon1 js-show-header-dropdown" alt="ICON">

	<div class="header-box header-dropdown">

		<div class="header-cart-item-name">
			{{ Auth::user()->sales_name }}
			@if(Auth::user()->nickname)
				({{ Auth::user()->nickname }})
			@endif
		</div>

		<div class="header-cart-buttons">
			<div class="header-box-wrapbtn">
				<a href="{{ route('logout') }}" class="flex-c-m size1 bg1 bo-rad-20 hov1 s-text1 trans-0-4" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
					登出
				</a>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
					{{ csrf_field() }}
				</form>
			</div>
		</div>

	</div>
</div>

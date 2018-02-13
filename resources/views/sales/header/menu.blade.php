<div class="wrap_menu">
	<nav class="menu">
		<ul class="main_menu">
			<li @if(Request::path() == 'bulletin') class="sale-noti"@endif>
				<a href="{{ url('bulletin') }}">公告</a>
			</li>

			<li @if(Request::path() == 'product' || Request::is('product/*')) class="sale-noti"@endif>
				<a href="{{ url('product') }}">商品瀏覽</a>
			</li>

			<li @if(Request::path() == 'picking' || Request::is('picking/*') || Request::is('cart/*')) class="sale-noti"@endif>
				<a href="{{ url('picking') }}">領貨作業</a>
			</li>

			<li @if(Request::path() == 'record') class="sale-noti"@endif>
				<a href="{{ url('record') }}">領貨記錄</a>
			</li>

		</ul>
	</nav>
</div>

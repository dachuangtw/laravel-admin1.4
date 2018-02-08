<div class="wrap_menu">
	<nav class="menu">
		<ul class="main_menu">
			<li @if(Request::path() == 'bulletin') class="sale-noti"@endif>
				<a href="{{ url('bulletin') }}">公告</a>
				<!-- <ul class="sub_menu">
					<li><a href="{{ url('/home') }}">Home</a></li>
				</ul> -->
			</li>

			<li @if(Request::path() == 'product') class="sale-noti"@endif>
				<a href="{{ url('product') }}">商品</a>
			</li>

			<li @if(Request::path() == 'record') class="sale-noti"@endif>
				<a href="{{ url('record') }}">領貨記錄</a>
			</li>

		</ul>
	</nav>
</div>

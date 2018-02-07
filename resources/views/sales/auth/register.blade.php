@extends('sales.layouts.blank')

@section('content')
<section class="bgwhite p-t-30 p-b-30">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
			</div>

			<div class="col-md-6">
				<form class="leave-comment" method="POST" action="{{ route('register') }}">
					{{ csrf_field() }}
					<h4 class="m-text26 p-b-36 p-t-15 t-center">
						註冊業務帳號
					</h4>

					<label>姓名</label>
					@if ($errors->has('name'))
					<strong class="m-text8">({{ $errors->first('name') }})</strong>
					@endif
					<div class="bo4 of-hidden size15 m-b-20">
						<input class="sizefull s-text7 p-l-22 p-r-22" type="text" name="name" value="{{ old('name') }}" required autofocus>
					</div>

					<label>暱稱</label>
					@if ($errors->has('nickname'))
					<strong class="m-text8">({{ $errors->first('nickname') }})</strong>
					@endif
					<div class="bo4 of-hidden size15 m-b-20">
						<input class="sizefull s-text7 p-l-22 p-r-22" type="text" name="nickname" value="{{ old('nickname') }}">
					</div>

					<label>倉庫id</label>
					@if ($errors->has('house'))
					<strong class="m-text8">({{ $errors->first('house') }})</strong>
					@endif
					<div class="bo4 of-hidden size15 m-b-20">
						<input class="sizefull s-text7 p-l-22 p-r-22" type="number" name="house" value="{{ old('house') }}" required>
					</div>

					<label>帳號</label>
					@if ($errors->has('account'))
					<strong class="m-text8">({{ $errors->first('account') }})</strong>
					@endif
					<div class="bo4 of-hidden size15 m-b-20">
						<input class="sizefull s-text7 p-l-22 p-r-22" type="text" name="account" value="{{ old('account') }}" required>
					</div>

					<label>密碼</label>
					@if ($errors->has('password'))
					<strong class="m-text8">({{ $errors->first('password') }})</strong>
					@endif
					<div class="bo4 of-hidden size15 m-b-20">
						<input class="sizefull s-text7 p-l-22 p-r-22" type="password" name="password" required>
					</div>

					<label>密碼確認</label>
					@if ($errors->has('password_confirmation'))
					<strong class="m-text8">({{ $errors->first('password_confirmation') }})</strong>
					@endif
					<div class="bo4 of-hidden size15 m-b-20">
						<input class="sizefull s-text7 p-l-22 p-r-22" type="password" name="password_confirmation" required>
					</div>

					{{-- @if (count($errors) > 0)
					<span class="m-text8">
						@php var_export($errors) @endphp
						<strong>has error!</strong>
					</span>
					@endif --}}
					<button type="submit" class="flex-c-m size2 bg1 bo-rad-23 hov1 m-text3 trans-0-4">
						註冊
					</button>
				</form>
			</div>
		</div>
	</div>
</section>
@endsection

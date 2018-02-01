@extends('sales.layouts.blank')

@section('content')
<section class="bgwhite p-t-66 p-b-60">
	<div class="container">
		<div class="row">
			<div class="col-md-3 p-b-30">
			</div>

			<div class="col-md-6 p-b-30">
				<form class="leave-comment" method="POST" action="{{ route('login') }}">
					{{ csrf_field() }}
					<h4 class="m-text26 p-b-36 p-t-15  t-center">
						登入
					</h4>

					<label>帳號</label>
					@if ($errors->has('sales_id'))
					<strong class="m-text8">({{ $errors->first('sales_id') }})</strong>
					@endif
					<div class="bo4 of-hidden size15 m-b-20">
						<input class="sizefull s-text7 p-l-22 p-r-22" type="text" name="sales_id" value="{{ old('sales_id') }}" required autofocus>
					</div>

					<label>密碼</label>
					@if ($errors->has('password'))
					<strong class="m-text8">({{ $errors->first('password') }})</strong>
					@endif
					<div class="bo4 of-hidden size15 m-b-20">
						<input class="sizefull s-text7 p-l-22 p-r-22" type="password" name="password" required>
					</div>

					@if (count($errors) > 0)
					<span class="m-text8">
						@php var_export($errors) @endphp
					</span>
					@endif
					<button type="submit" class="flex-c-m size2 bg1 bo-rad-23 hov1 m-text3 trans-0-4">
						登入
					</button>
					<!-- <a class="btn btn-link" href="{{ route('password.request') }}">
						忘記密碼?
					</a> -->
				</form>
			</div>
		</div>
	</div>
</section>
@endsection

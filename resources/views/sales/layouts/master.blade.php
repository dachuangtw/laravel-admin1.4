<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<title>{{ config('sales.name', 'dachuang') }}</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="icon" type="image/png" href="{{ asset(config('sales.asset_path') . 'images/icons/favicon.png') }}"/>

	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'vendor/bootstrap/css/bootstrap.min.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'fonts/themify/themify-icons.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'fonts/elegant-font/html-css/style.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'vendor/animate/animate.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'vendor/css-hamburgers/hamburgers.min.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'vendor/animsition/css/animsition.min.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'vendor/select2/select2.min.css') }}">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'vendor/slick/slick.css') }}">

	@yield('css')

	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'css/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'css/main.css') }}">
</head>
<body class="animsition">

	<!-- Header -->
	<header class="header1">
		<!-- Header desktop -->
		<div class="container-menu-header">
			<div class="topbar">
				<div class="topbar-child2">
					<span class="topbar-email">
					</span>
				</div>
			</div>

			<div class="wrap_header">
				<!-- Logo -->
				<a href="{{ url('/') }}" class="logo">
					<h3>Dachuang</h3>
				</a>

				<!-- Menu -->
				@includeIf('sales.parts.header-menu')

				<!-- Header Icon -->
				<div class="header-icons">

					@includeIf('sales.parts.header-account')

					@if(Request::path() == 'picking')
					@includeIf('sales.parts.header-shopping')
					@endif

				</div>
			</div>
		</div>

		<!-- Header Mobile -->
		<div class="wrap_header_mobile">
			<!-- Logo moblie -->
			<a href="{{ url('/') }}" class="logo-mobile">
				<h3>Dachuang</h3>
			</a>

			<!-- Button show menu -->
			<div class="btn-show-menu">
				<!-- Header Icon mobile -->
				<div class="header-icons-mobile">

					@includeIf('sales.parts.header-account')

					@if(Request::path() == 'picking')
					@includeIf('sales.parts.header-shopping')
					@endif

				</div>

				<div class="btn-show-menu-mobile hamburger hamburger--squeeze">
					<span class="hamburger-box">
						<span class="hamburger-inner"></span>
					</span>
				</div>
			</div>
		</div>

		<!-- Menu Mobile -->
		@includeIf('sales.parts.header-menu-mobile')

	</header>

	<!-- Title Page -->
	@yield('title-page')

	<!-- Content page -->
	@yield('content')

	<!-- Footer -->
	<footer class="bg6 p-t-45 p-b-43 p-l-45 p-r-45">
		<div class="t-center p-l-15 p-r-15">
			<div class="t-center s-text8 p-t-20">
				Copyright © 2018 All rights reserved. | Dachuang
			</div>
		</div>
	</footer>

	<!-- Back to top -->
	<div class="btn-back-to-top bg0-hov" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="fa fa-angle-double-up" aria-hidden="true"></i>
		</span>
	</div>

	<!-- Container Selection -->
	<div id="dropDownSelect1"></div>
	<div id="dropDownSelect2"></div>

<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/jquery/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/animsition/js/animsition.min.js') }}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/bootstrap/js/popper.js') }}"></script>
	<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/select2/select2.min.js') }}"></script>
	<script type="text/javascript">
		$(".selection-1").select2({
			minimumResultsForSearch: 20,
			dropdownParent: $('#dropDownSelect1')
		});

		$(".selection-2").select2({
			minimumResultsForSearch: 20,
			dropdownParent: $('#dropDownSelect2')
		});
	</script>
<!--===============================================================================================-->

	@yield('script')

	<script src="{{ asset(config('sales.asset_path') . 'js/main.js') }}"></script>

</body>
</html>

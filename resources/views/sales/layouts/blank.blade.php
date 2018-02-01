<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<title>{{ config('sales.name', 'dachuang') }}</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
<!--===============================================================================================-->
	<!-- <link rel="icon" type="image/png" href="{{ asset(config('sales.asset_path') . 'images/icons/favicon.png') }}"/> -->
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'vendor/bootstrap/css/bootstrap.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
<!--===============================================================================================-->
	<!-- <link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'fonts/themify/themify-icons.css') }}"> -->
<!--===============================================================================================-->
	<!-- <link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}"> -->
<!--===============================================================================================-->
	<!-- <link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'fonts/elegant-font/html-css/style.css') }}"> -->
<!-- =============================================================================================== -->
	<!-- <link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'vendor/animate/animate.css') }}"> -->
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'vendor/css-hamburgers/hamburgers.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'vendor/animsition/css/animsition.min.css') }}">
<!--===============================================================================================-->
	<!-- <link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'vendor/select2/select2.min.css') }}"> -->
<!--===============================================================================================-->
	<!-- <link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'vendor/slick/slick.css') }}"> -->
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'css/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'css/main.css') }}">
<!--===============================================================================================-->
</head>
<body class="animsition">

	@auth
	<!-- Header -->
	<header class="header1">
		<!-- Header desktop -->
		<div class="container-menu-header">

			<div class="wrap_header">
				<!-- Logo -->
				<a href="{{ url('/') }}" class="logo">
					<h2>Dachuang</h2>
					<!-- <img src="{{ asset(config('sales.asset_path') . 'images/icons/logo.png') }}" alt="IMG-LOGO"> -->
				</a>

				@includeIf('sales.parts.header-menu')

				<!-- Header Icon -->
				<div class="header-icons">

					@includeIf('sales.parts.header-account')

					{{-- @includeIf('sales.parts.header-shopping') --}}

				</div>

			</div>
		</div>

		<!-- Header Mobile -->
		<div class="wrap_header_mobile">
			<!-- Logo moblie -->
			<a href="{{ url('/') }}" class="logo-mobile">
				<h2>Dachuang</h2>
				<!-- <img src="{{ asset(config('sales.asset_path') . 'images/icons/logo.png') }}" alt="IMG-LOGO"> -->
			</a>

			<!-- Button show menu -->
			<div class="btn-show-menu">
				<!-- Header Icon mobile -->
				<div class="header-icons-mobile">

					@includeIf('sales.parts.header-account')

					{{-- @includeIf('sales.parts.header-shopping') --}}

				</div>

				<div class="btn-show-menu-mobile hamburger hamburger--squeeze">
					<span class="hamburger-box">
						<span class="hamburger-inner"></span>
					</span>
				</div>
			</div>
		</div>

		@includeIf('sales.parts.header-menu-mobile')

	</header>
	@endauth

	@yield('content')

	<!-- Container Selection -->
	<!-- <div id="dropDownSelect1"></div>
	<div id="dropDownSelect2"></div> -->

<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/jquery/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/animsition/js/animsition.min.js') }}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/bootstrap/js/popper.js') }}"></script>
	<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<!--===============================================================================================-->
	<!-- <script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/select2/select2.min.js') }}"></script> -->
	<!-- <script type="text/javascript">
		$(".selection-1").select2({
			minimumResultsForSearch: 20,
			dropdownParent: $('#dropDownSelect1')
		});

		$(".selection-2").select2({
			minimumResultsForSearch: 20,
			dropdownParent: $('#dropDownSelect2')
		});
	</script> -->

	<script src="{{ asset(config('sales.asset_path') . 'js/main.js') }}"></script>

</body>
</html>

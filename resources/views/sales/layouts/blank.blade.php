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
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'vendor/animsition/css/animsition.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'css/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset(config('sales.asset_path') . 'css/main.css') }}">
<!--===============================================================================================-->
</head>
<body class="animsition">

	@yield('content')

<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/jquery/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/animsition/js/animsition.min.js') }}"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/bootstrap/js/popper.js') }}"></script>
	<script type="text/javascript" src="{{ asset(config('sales.asset_path') . 'vendor/bootstrap/js/bootstrap.min.js') }}"></script>

	<script src="{{ asset(config('sales.asset_path') . 'js/main.js') }}"></script>

</body>
</html>

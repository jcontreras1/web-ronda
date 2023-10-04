<!DOCTYPE html>
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark"> --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>@yield('titulo') {{config('app.name', 'Rondin')}}</title>
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<!-- Styles -->
	<link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="h-100">
	@include('sweetalert::alert')
	<div class="min-vh-100 d-flex flex-column">
		@guest
		@include('components.menu.navbar_simple')
		@else
		@include('components.menu.navbar')
		@endguest
		<main>
		@yield('content')
		</main>
		@stack('scripts')
		<script src="{{ mix('js/app.js') }}"></script>
		@include('components.misc.footer')
	</div>
</body>
</html>

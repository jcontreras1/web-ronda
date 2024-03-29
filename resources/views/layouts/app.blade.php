<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>@yield('titulo') {{config('app.name', 'Offiweb')}}</title>
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<!-- Scripts -->
	
	<script type="text/javascript" src="{{asset('/js/theme-switcher.js')}}"></script>
	@stack('head')
	@yield('head')
	<!-- Fonts -->
	{{-- <link rel="dns-prefetch" href="//fonts.gstatic.com"> --}}
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
	<!-- Styles -->
	<link href="{{ mix('css/app.css') }}" rel="stylesheet">
	<style type="text/css">
		.bg-purple{
			background-color: #909CAE;
		}
		.bg-palta{
			background-color: #8FC6B1;
		}

		.card {
			border-radius: 2px !important;
		}
		.card.info{
			border-left: 4px solid #2d89c8;
		}
		.card.dark{
			border-left: 4px solid #6c757d;
		}

		.row-click{
			cursor: pointer;
		}
		.card.indigo{
			border-left: 4px solid #9561e2;
		}
	</style>
</head>
<body class="h-100">
	@include('sweetalert::alert')
	<div id="app" class="min-vh-100 d-flex flex-column">
		@guest
		@include('components.menu.navbar_simple')
		@else
		@include('components.menu.navbar')
		@endguest
		<main class="">
			<div class="container-fluid">
				@yield('content')
			</div>
		</main>
		@include('components.misc.footer')
	</div>
	<script src="{{ mix('js/app.js') }}"></script>
	<script type="text/javascript">
		$(function () {
			$("[data-toggle=tooltip").tooltip();
			$('.btn').click(function(){$(this).blur()});
			$("[data-toggle=popover]").popover({
				html: true,
				sanitize: false
			});
			$('.modal').on('shown.bs.modal', function() {
				$('.primerCampo').focus();
			})
		});
	</script>
	@yield('scripts')
	@stack('scripts')
</body>
</html>

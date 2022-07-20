@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<h1>Sistema de Control</h1>
			<div class="py-4"></div>
			@guest
			<div class="row">
				@if (Route::has('login'))
				<div class="col-6 col-xs-12">
					<a href="{{ route('login') }}" class="text-dark" style="text-decoration: none;">
						<div class="card info">
							<div class="card-body">
								<div class="row">
									<div class="col-3 col-xs-12">
										<div class="display-1">
											<i class="bi bi-person"></i>
										</div>
									</div>
									<div class="col-9 col-xs-12">
										<div class="py-2"></div>
										<h3 class="card-title">{{ __('Login') }}</h3>
										<p class="d-none d-sm-block card-text">Acceder al sistema con Usuario y Contraseña</p>
									</div>
								</div>
							</div>
						</div>
					</a>
				</div>
				@endif
				@if (Route::has('register'))
				<div class="col-6 col-xs-12">
					<a href="{{ route('register') }}" class="text-dark" style="text-decoration: none;">
						<div class="card info">
							<div class="card-body">
								<div class="row">
									<div class="col-3 col-xs-12">
										<div class="display-1">
											<i class="bi bi-person-plus"></i>
										</div>
									</div>
									<div class="col-9 col-xs-12">
										<div class="py-2"></div>
										<h3 class="card-title">{{ __('Register') }}</h3>
										<p class="d-none d-sm-block card-text">Registrar un usuario nuevo</p>
									</div>
								</div>
							</div>
						</div>
					</a>
				</div>
				@endif
			</div>
			@else
			@include('layouts.inicio')
			@endguest
		</div>
	</div>
@endsection

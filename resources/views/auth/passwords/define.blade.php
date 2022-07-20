@extends('layouts.login')
@section('content')

<style type="text/css">
	html {
		background-color: #fff;
		color: #636b6f;
		font-family: 'Nunito', sans-serif;
		font-weight: 200;
		height: 100%;
		margin: 0;
	}

	.no-gutter.row,
	.no-gutter.container,
	.no-gutter.container-fluid{
		margin-left: 0;
		margin-right: 0;
	}

	.no-gutter>[class^="col-"]{
		padding-left: 0;
		padding-right: 0;
	}
	.title {
		font-size: 25px;
		padding-top: 20px;
	}
</style>

<div class="row no-gutter" style="height: 100%;">
	<!-- start carousel -->
	<div class="col-12 col-md-8 carousel slide d-none d-md-block " data-ride="carousel" style="height: 100vh;">
		<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">			
			<div class="carousel-inner">
				@for($i=1;$i<=6;$i++)
				<div class="carousel-item @if($i== 1)active @endif">
					<img src="{{asset('assets/img/frames/'.$i.'.jpg')}}" class="d-block w-100" alt="...">
				</div>
				@endfor
			</div>      
		</div>
	</div><!-- end carousel -->

	<div class="col-12 col-md-4 d-flex flex-column min-vh-100 justify-content-center align-items-center">
		<div class="container">
			<div class="display-4 text-center">
				<a href="{{url('/')}}" style="text-decoration: none;">					
					{{config('app.name')}}
				</a>
			</div>
			<div class="py-4"></div>

			<form method="POST" action="{{ route('password.update') }}">
				@csrf
				<input type="hidden" name="token" value="{{ $token }}">
				<section class="container">
				<div class="row">
					<div class="col-12">
						<label for="email">{{ __('E-Mail Address') }}</label>
						<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>
					</div>
				</div>
				<div class="py-2"></div>
				<div class="row">
					<div class="col-12">
						<label for="password">Clave</label>
						<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required value="{{ $password ?? old('password') }}">
					</div>
				</div>
				<div class="py-2"></div>
				<div class="row">
					<div class="col-12">
						<label for="password-confirm">Confirmar Clave</label>
						<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
					</div>
				</div>
				<div class="py-2"></div>
				<div class="row float-end">
					<div class="col-12">
						<button type="submit" class="btn btn-primary">
							Definir Clave
						</button>
					</div>
				</div>
				</section>
			</form>
		</div>
	</div>
</div>
</div>
@endsection

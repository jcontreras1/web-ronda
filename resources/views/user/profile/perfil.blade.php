@extends('layouts.app')
@section('content')
@include('user.modals.editar_mi_clave')
@section('titulo', 'Mi Perfil - ')
<div class="container">

	<h3>
		Editar Perfil
		<span class="float-end">
			@include('components.misc.backbutton', ['url' => route('home')])
		</span>
	</h3>
	<hr>
	<form action="{{route('user.profile.update')}}" method="POST">
		@csrf
		@method('PATCH')
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<h5>Perfil del Usuario</h5>
				<hr>
				<div class="row">
					<div class="col-12">
						<strong>Nombre</strong>
						<input type="text" name="nombre" class="form-control @if($errors->has('nombre')) is-invalid @endif" placeholder="Nombre" value="{{$user->nombre}}">
					</div>

					<div class="col-12">
						<strong>Apellido</strong>
						<input type="text" name="apellido" class="form-control" placeholder="Apellido" value="{{$user->apellido}}">
					</div>

					<div class="col-12">
						<strong>Email</strong>
						<input type="text" name="email" class="form-control @if($errors->has('email')) is-invalid @endif" placeholder="E-mail" value="{{$user->email}}">
					</div>

					<div class="col-12">
						<strong>DNI</strong>
						<input type="text" value="{{$user->dni}}" name="dni" class="form-control @if($errors->has('dni')) is-invalid @endif" placeholder="DNI">
					</div>
					<div class="col-12">
						<strong>Teléfono celular</strong>
						<input type="text" value="{{$user->telefono}}" name="telefono" class="form-control" placeholder="Teléfono Celular">
					</div>
					<div class="col-12">
						<strong>Fecha de Nacimiento</strong>
						<input type="date" value="{{ $user->fecha_nacimiento ?? date('Y-m-d') }}" name="fecha_nacimiento" class="form-control" >
					</div>
				</div>
				<div class="py-2"></div>
			</div>

			<div class="col-xs-12 col-md-6">
				<h5>Cargo</h5>
				<hr>
				@if(count($user->tipos_usuario) == 0)
				<small>Sin cargos</small>
				@else
				<table class="table table-striped table-sm">
					<tbody>
						@foreach($user->tipos_usuario as $tipo_usuario)
						<tr>
							<td>{{$tipo_usuario->nombre}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				@endif
			</div> 
		</div>
		<hr>
		<div class="row">
			<div class="col-12">
				<div class="float-right">
					<button type="submit" class="btn btn-success">Guardar</button>
					<button type="button" id="editar_contraseña_usuario" class="btn btn-warning">Cambiar contraseña</button>
				</div> 
			</div>
		</div>
	</form>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$('#editar_contraseña_usuario').click(function(){
			$('#EditarPassUsuario').modal('show');
		});
	});
</script>
@endsection



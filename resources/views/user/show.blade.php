@extends('layouts.app')
@section('content')
@section('titulo', 'Usuario - ')
<h3>
	Ver Usuario
	<span class="float-right">
		<a class="btn btn-primary" href="{{ url('users') }}"><i class="fas fa-arrow-left"></i></a>
	</span>
</h3>
<hr>
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-6">
			<div class="row">
				<div class="col-12">
					<strong>DNI:</strong>
					{{ $user->dni }}
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<strong>Nombre:</strong>
					{{ $user->name }}
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<strong>Apellido:</strong>
					{{ $user->lastname }}
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<strong>Email:</strong>
					<a target="_blank" href="mailto:{{$user->email}}">{{ $user->email }}</a>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<strong>Roles:</strong>
					@if(count($user->roles) == 0)
					<small>Sin roles</small>
					@else
					<ul>
						@foreach($user->roles as $rol)
						<li>{{$rol->nombre}}</li>
						@endforeach
					</ul>
					<br/>
					@endif
				</div>
			</div>
		</div>

		<div class="col-xs-12 col-6">
			<div class="row">
				<div class="col-12">
					<div class="float-right">
						@if(!sistema_vencido())
						<a data-toggle="tooltip" title="Editar" class="btn btn-warning" href="{{ route('users.edit',$user->id) }}"><i class="fas fa-user-edit"></i>
						</a>
						@endif
					</div>
				</div>
			</div>

		</div>
	</div>


	<hr>   
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		console.log('app');
	});
</script>
@endsection
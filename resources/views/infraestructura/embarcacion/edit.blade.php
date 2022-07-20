@extends('layouts.app')
@section('content')
@section('titulo', 'Embarcacion - ')
<div class="container">
	<h3>
		Editar embarcación: <strong>{{$embarcacion->nombre}}</strong>
		<span class="float-end">
			@include('components.misc.backbutton', ['url' => route('embarcacion.index')])
		</span>
	</h3>
	<div class="py-2"></div>
	<hr>

	<form method="POST" action="{{route('embarcacion.update', $embarcacion)}}">
		@csrf
		@method('PATCH')
		<div class="row">
			<div class="col-12">
				<label>Nombre @include('components.misc.required')</label>
				<input autocomplete="off" type="text" value="{{$embarcacion->nombre}}" class="primerCampo form-control @if($errors->has('nombre')) is-invalid @endif" name="nombre" required>
			</div>
			<div class="col-12">
				<label>Capacidad @include('components.misc.required')</label>
				<input autocomplete="off" type="number" value="{{$embarcacion->capacidad}}" class="form-control @if($errors->has('capacidad')) is-invalid @endif" name="capacidad" required>
			</div>
			<div class="col-12">
				<label>Eslora</label>
				<input autocomplete="off" type="number" value="{{$embarcacion->eslora}}" step="0.01" class="form-control @if($errors->has('eslora')) is-invalid @endif" name="eslora">
			</div>
			<div class="col-12">
				<label>Manga</label>
				<input autocomplete="off" type="number" value="{{$embarcacion->manga}}" step="0.01" class="form-control @if($errors->has('manga')) is-invalid @endif" name="manga">
			</div>
			<div class="col-12">
				<label>Puntal</label>
				<input autocomplete="off" type="number" value="{{$embarcacion->puntal}}" step="0.01" class="form-control @if($errors->has('puntal')) is-invalid @endif" name="puntal">
			</div>
			<div class="col-12">
				<label>Matrícula</label>
				<input autocomplete="off" type="text" value="{{$embarcacion->matricula}}" class="form-control @if($errors->has('matricula')) is-invalid @endif" name="matricula">
			</div>
			<div class="col-12">
				<label>Horas</label>
				<input autocomplete="off" type="number" value="{{$embarcacion->horas}}" class="form-control @if($errors->has('horas')) is-invalid @endif" name="horas">
			</div>
		</div>
		<div class="py-2"></div>
		<button type="submit" class="btn btn-success">Guardar</button>
	</form>

</div>

@endsection
@extends('layouts.app')
@section('titulo', 'Agencia - ')
@section('content')
<div class="container">
	<h3>
		<i class="fas fa-users"></i> Agencias
		<span class="float-end">
			@include('components.misc.backbutton', ['url' => route('agencia.index')])
		</span>
	</h3>
	<hr>
	<form method="POST" action="{{route('agencia.update', $agencia)}}">
		@csrf
		@method('PATCH')
		<div class="row">
			<div class="col-12">
				<label>Razón Social @include('components.misc.required')</label>
				<input autocomplete="off" type="text" class="primerCampo form-control @if($errors->has('razon_social')) is-invalid @endif" value="{{$agencia->razon_social}}" name="razon_social" required>
			</div>
			<div class="col-12">
				<label>Cuit</label>
				<input autocomplete="off" type="text" class="form-control @if($errors->has('cuit')) is-invalid @endif" value="{{$agencia->cuit}}" name="cuit">
			</div>
			<div class="col-12">
				<label>Email</label>
				<input autocomplete="off" type="email" class="form-control @if($errors->has('email')) is-invalid @endif" value="{{$agencia->email}}" name="email">
			</div>
			<div class="col-12">
				<label>Teléfono</label>
				<input autocomplete="off" type="text" class="form-control @if($errors->has('telefono')) is-invalid @endif" value="{{$agencia->telefono}}" name="telefono">
			</div>
		</div>
		<div class="py-2"></div>
		<button type="submit" class="btn btn-success">Aceptar</button>
	</form>
</div>
@endsection
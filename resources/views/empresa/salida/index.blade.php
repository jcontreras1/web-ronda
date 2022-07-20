@extends('layouts.app')
@section('content')
@section('titulo', 'Salidas - ')
@include('empresa.salida.modals.create')
<div class="container">
	<h3>
		Salidas del día
		<span class="float-end">
			<a class="btn btn-success" data-toggle="tooltip" title="Crear varias salidas" href="{{route('salida.create')}}"><i class="bi bi-calendar-week-fill"></i></a>
			<button id="btn_agregar_salida" class="btn btn-success" data-toggle="tooltip" title="Crear salida"><i class="bi bi-plus"></i></button>
			<a href="{{route('salidas.index')}}" class="btn btn-primary" data-toggle="tooltip" title="Todas las salidas"><i class="bi bi-list-check"></i></a>
			@include('components.misc.backbutton', ['url' => url('/')])
		</span>
	</h3>
	<hr>
	<div class="row">
		@forelse($salidas as $salida)
		<x-salida.card-salida :salida="$salida"/>
		@empty
		<p class="lead">
			<em>No hay salidas para el día de hoy</em>
		</p>
		@endforelse
	</div>
	<div class="py-2"></div>
	@if(count($salidas_cerradas) > 0)
	<h3>Salidas Cerradas</h3>
	@endif
	<div class="row">
		@forelse($salidas_cerradas as $salida)
		<x-salida.card-salida :salida="$salida"/>
		@empty
		<p class="lead">
			<em>No hay salidas cerradas, hoy</em>
		</p>
		@endforelse
	</div>
</div>
<form id="form_salida_cerrar" method="POST">@csrf @method('PATCH') <input type="hidden" name="cerrada" value="1"></form>
<form id="form_salida_abrir" method="POST">@csrf @method('PATCH') <input type="hidden" name="cerrada" value="0"></form>

@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$('#btn_agregar_salida').click(function(){
			$('#mdl_salida_create').modal('show');
		});
		$('.btn_salida_cerrar').click(function(){
			let url = $(this).data('url');
			Swal.fire({
				icon: 'question',
				title: '¿Cerrar Salida?',
				showCancelButton: true,
				confirmButtonText: 'Si',
				cancelButtonText: 'No',
			}).then((result) => {
				if (result.isConfirmed) {
					console.log(url);
					$('#form_salida_cerrar').attr('action', url);
					$('#form_salida_cerrar').submit();
				}
			});
		});		
		$('.btn_salida_abrir').click(function(){
			let url = $(this).data('url');
			Swal.fire({
				icon: 'question',
				title: '¿Abrir Salida?',
				showCancelButton: true,
				confirmButtonText: 'Si',
				cancelButtonText: 'No',
			}).then((result) => {
				if (result.isConfirmed) {
					console.log(url);
					$('#form_salida_abrir').attr('action', url);
					$('#form_salida_abrir').submit();
				}
			});
		});
	});
</script>
@endsection
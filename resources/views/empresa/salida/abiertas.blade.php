@extends('layouts.app')
@section('content')
@include('empresa.salida.modals.create')
@section('titulo', 'Salidas - ')
<div class="container">
	<h3>
		Salidas abiertas
		<span class="float-end">
			<a class="btn btn-success" data-toggle="tooltip" title="Crear varias salidas" href="{{route('salida.create')}}"><i class="bi bi-calendar-week-fill"></i></a>
			<button id="btn_agregar_salida" class="btn btn-success" data-toggle="tooltip" title="Crear salida"><i class="bi bi-plus"></i></button>
			@include('components.misc.backbutton', ['url' => route('salida.index')])
		</span>
	</h3>
	<hr>
	<div class="table-responsive">		
		<table class="table table-striped" id="tabla">
			<thead>
				<tr>
					<th>Fecha/Hora</th>
					<th>Disponibilidad</th>
					<th>Embarcación</th>
					<th>Opciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach($salidas as $salida)
				<tr>
					<td data-order="{{date('YmdHi', strtotime($salida->fecha . ' ' . $salida->hora))}}">{{date('d/m/Y H:i', strtotime($salida->fecha . ' ' . $salida->hora))}}</td>
					<td>{{$salida->disponibilidad}}</td>
					<td>{{ucwords($salida->embarcacion->nombre)}}</td>
					<td>
						<a data-toggle="tooltip" title="Vender" href="{{route('venta.create', $salida)}}" class="btn btn-sm btn-success"><i class="bi bi-credit-card-fill"></i></a>
						@if(count($salida->pasajeros) == 0)
						<button data-url="{{route('salida.destroy', $salida)}}" data-toggle="tooltip" title="Eliminar salida" class="btn btn-sm btn-danger btn_eliminar_salida"><i class="bi bi-trash"></i></button>
						@else
						<a href="{{route('salida.pasajeros', $salida)}}"><button class="btn btn-sm btn-primary" data-toggle="tooltip" title="Ver pasajeros"><i class="bi bi-list-check"></i></button></a>
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="py-2"></div>

</div>
<form id="form_salida_eliminar" method="POST">@csrf @method('DELETE')</form>
<form id="form_salida_cerrar" method="POST">@csrf @method('PATCH') <input type="hidden" name="cerrada" value="1"></form>
<form id="form_salida_abrir" method="POST">@csrf @method('PATCH') <input type="hidden" name="cerrada" value="0"></form>

@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){

		$('#tabla').DataTable({
			language : {
				url : '{{asset('assets/dt.spanish.json')}}'
			},
			"pageLength": 50
		});

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
		$('.btn_eliminar_salida').click(function(){
			let url = $(this).data('url');
			$('#form_salida_eliminar').attr('action', url);
			$('#form_salida_eliminar').submit();
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
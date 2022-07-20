@extends('layouts.app')
@section('content')
@section('titulo', 'Salida - ')
@include('empresa.salida.modals.edit')
@include('empresa.salida.modals.vehiculo_add')
@include('empresa.salida.modals.tripulante_add')
<div class="container">
	<h3>
		Salida | <small class="text-muted h6">#{{$salida->id}}</small>
		<span class="float-end">
			@include('components.generales.backbutton', ['url' => route('empresa.show', $salida->empresa)])
		</span>
	</h3>
	<div class="py-1"></div>
	<div class="card indigo">
		<div class="card-body">
			@if(!$salida->cerrada)
			<span class="float-end">
				<button data-toggle="tooltip" id="editar_salida" title="Editar salida" class="btn btn-warning"><i class="bi bi-pencil-fill"></i></button>
				<button data-toggle="tooltip" id="btn_cerrar_salida" title="Cerrar salida" class="btn btn-danger"><i class="bi bi-x-lg"></i></button>
			</span>
			@endif
			<div class="row">
				<div class="col-12 col-md-6">
					<div class="h3">{{fecha_string_completa($salida->fecha)}}</div>
					<div class="h4">{{date('H:i', strtotime($salida->hora))}}</div>
				</div>
				<div class="col-12 col-md-6">
					<div>Pasajeros: <strong>0</strong></div>
					<div>Vehiculos: <strong>{{count($salida->vehiculo_salida)}}</strong></div>
					<div>Capacidad Total: <strong>{{$capacidad_total}}</strong></div>
				</div>
			</div>
		</div>
	</div>
	<hr>
	<h4>
		Vehículos 
		@if(!$salida->cerrada)
		<button class="btn btn-primary btn-sm" id="btn_agregar_vehiculo_salida">Agregar <i class="bi bi-chevron-down"></i></button>
		@endif
	</h4>
	
	<div class="py-2"></div>
	<div class="row">
		@foreach($salida->vehiculo_salida as $vehiculo)
		<div class="col-12 col-md-3">
			<div class="card indigo h-100">
				<div class="card-body">
					@if(!$salida->cerrada)
					<span class="float-end">
						<div class="btn-group">
							
							<button class="btn btn-primary btn-sm btn_agregar_personal_vehiculo_salida" data-vehiculo_salida="{{$vehiculo->id}}" data-toggle="tooltip" title="Agregar personal"><i class="bi bi-person-plus-fill"></i></button>
							<button class="btn btn-danger btn-sm btn_eliminar_vehiculo_salida" data-url="{{route('vehiculo_salida.destroy', $vehiculo)}}" data-toggle="tooltip" title="Quitar Vehículo"><i class="bi bi-trash"></i></button>
						</div>
					</span>
					@endif
					<div>{{$vehiculo->vehiculo->get_modelo->marca->nombre}} {{$vehiculo->vehiculo->get_modelo->nombre}}</div>
					<div>Capacidad: <strong>{{$vehiculo->vehiculo->capacidad}} Pasajeros</strong></div>
					<div>{{$vehiculo->vehiculo->dominio}}</div>
					<hr>
					<table>
						@foreach($vehiculo->tripulantes as $tripulante)
						<tr>
							<td class="lead"><span class="badge bg-dark">{{ucfirst($tripulante->tipo_tripulante->nombre)}}</span></td>
							<td>&nbsp;<strong>{{ucwords($tripulante->usuario->name)}} {{ucwords($tripulante->usuario->lastname)}}</strong></td>
							@if(!$salida->cerrada)
							<td class="lead"><a href="#" class="bnt_eliminar_tripulante" data-url="{{route('tripulante.destroy', $tripulante)}}"><i class="bi bi-trash text-danger"></i></a>
							</td>
							@endif
						</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>
		@endforeach
	</div>
</div>
@if(!$salida->cerrada)
<form id="form_delete_tripulante" method="POST"> @csrf @method('DELETE') </form>
<form id="form_delete_vehiculo_salida" method="POST"> @csrf @method('DELETE') </form>
<form action="{{route('salida.update', $salida)}}" id="form_cerrar_salida" method="POST"> @csrf @method('PUT')<input type="hidden" name="cerrada" value="1"></form>
@endif
@endsection
@if(!$salida->cerrada)
@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$('.btn_eliminar_vehiculo_salida').click(function(){
			let url = $(this).data('url');
			Swal.fire({
				icon: 'question',
				title: '¿Eliminar Vehículo?',
				showCancelButton: true,
				confirmButtonText: 'Si',
				cancelButtonText: 'No',
			}).then((result) => {
				/* Read more about isConfirmed, isDenied below */
				if (result.isConfirmed) {
					console.log(url);
					$('#form_delete_vehiculo_salida').attr('action', url);
					$('#form_delete_vehiculo_salida').submit();
				}
			});
		});
		$('.bnt_eliminar_tripulante').click(function(){
			let url = $(this).data('url');
			Swal.fire({
				icon: 'question',
				title: '¿Eliminar Tripulante?',
				showCancelButton: true,
				confirmButtonText: 'Si',
				cancelButtonText: 'No',
			}).then((result) => {
				/* Read more about isConfirmed, isDenied below */
				if (result.isConfirmed) {
					console.log(url);
					$('#form_delete_tripulante').attr('action', url);
					$('#form_delete_tripulante').submit();
				}
			});
		});
		$('#btn_cerrar_salida').click(function(){
			Swal.fire({
				icon: 'question',
				title: '¿Cerrar Salida?',
				showCancelButton: true,
				confirmButtonText: 'Si',
				cancelButtonText: 'No',
			}).then((result) => {
				/* Read more about isConfirmed, isDenied below */
				if (result.isConfirmed) {
					$('#form_cerrar_salida').submit();
				}
			});
		});
		$('.btn_agregar_personal_vehiculo_salida').click(function(){
			let vehiculo_salida = $(this).data('vehiculo_salida');
			$('#mdl_salida_tripulante_add').modal('show');
			$('#id_vehiculo_salida').val(vehiculo_salida);
		});
		$('#btn_agregar_vehiculo_salida').click(function(){
			$('#mdl_salida_vehiculo_add').modal('show');
		});
		$('#editar_salida').click(function(){
			$('#mdl_salida_edit').modal('show');
		});
	});
</script>
@endsection
@endif
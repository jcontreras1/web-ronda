@extends('layouts.app')
@section('titulo', 'Venta - ')
@section('content')
<div class="container">
	<h3>
		Venta <strong>#{{$venta->id}}</strong>
		<span class="float-end">
			@include('components.misc.backbutton', ['url' => route('caja.dia')])
		</span>
	</h3>
	<hr>
	<div class="card info">
		<div class="card-body">
			<div class="row">
				<div class="col-12 col-md-4">
					<table class="table">						
						<tr>
							<td>Cliente:</td>
							<td><strong>{{ucwords($venta->razon_social)}}</strong></td>
						</tr>
						<tr>
							<td>Cuit/DNI:</td>
							<td><strong>{{ucwords($venta->cuit)}}</strong></td>
						</tr>
						<tr>
							<td>Detalle</td>
							<td><strong>{{$venta->detalle}}</strong></td>
						</tr>
					</table>
				</div>
				<div class="col-12 col-md-4">
					<ul class="list-group list-group-flush">
						<li class="list-group-item">Anticipo: <strong>${{pesosargentinos($venta->anticipo)}}</strong></li>
						<li class="list-group-item">Medio de Pago: <strong>@if($venta->anticipo){{$venta->medio_pago_anticipo->nombre}}@else - @endif</strong></li>
						<li class="list-group-item">Observaciones (Anticipo): <strong>{{$venta->anticipo_observaciones ?? '-'}}</strong></li>
						<li class="list-group-item">Descuento: <strong>${{pesosargentinos($venta->descuento)}}</strong></li>
						<li class="list-group-item">Observaciones (Descuento): <strong>{{$venta->observacion_descuento ?? '-'}}</strong></li>
					</ul>
				</div>
				<div class="col-12 col-md-4">
					<ul class="list-group list-group-flush">
						<li class="list-group-item">Total: <strong>${{pesosargentinos($venta->total)}}</strong></li>
						<li class="list-group-item">Medios de pago: <strong>{{count($venta->abonos)}}</strong></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="py-1"></div>
	<div class="row">
		<div class="col-12 col-md-6">			
			<div class="card indigo">
				<div class="card-header">
					Pasajeros
				</div>
				<div class="card-body">
					<table class="table">
						<tr>
							<th>Nombre</th>
							<th>DNI</th>
							<th>Nacionalidad</th>
						</tr>
						@foreach($venta->pasajeros_venta as $pasajero)
						<tr>
							<td>{{ucwords($pasajero->pasajero->apellido . ' ' . $pasajero->pasajero->nombre)}}</td>
							<td>{{$pasajero->pasajero->dni}}</td>
							<td>{{$pasajero->pasajero->pais->nombre}}</td>
						</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6">			
			<div class="card indigo">
				<div class="card-header">
					Medios de Pago
				</div>
				<div class="card-body">
					<table class="table">
						<tr>
							<th>Medio de pago</th>
							<th>Observaciones</th>
							<th>Cotizacion</th>
							<th>importe</th>
						</tr>
						@foreach($venta->abonos as $abono)
						<tr>
							<td>{{$abono->medio_pago->nombre}}</td>
							<td>{{$abono->observaciones ?? '-'}}</td>
							<td>${{pesosargentinos($abono->cotizacion)}}</td>
							<td>
								<span @if($abono->cotizacion != 1) class="text-primary font-weight-bold" data-toggle="tooltip" title="En AR$: {{round(($abono->cotizacion * $abono->importe),2)}}" @endif>
									{{$abono->moneda->simbolo}}{{pesosargentinos($abono->importe)}}
								</span>
							</td>
						</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@extends('layouts.app')
@section('titulo', 'Agencia - ')
@section('content')
@include('empresa.agencia.modals.rendir')
<div class="container">
	<h3>
		<strong>{{$agencia->razon_social}}</strong>
		<span class="float-end">
			@include('components.misc.backbutton', ['url' => route('agencia.index')])
		</span>
	</h3>
	<hr>
	@if(count($vouchers_sin_rendir))
	@include('components.vouchers.sin-rendir')
	@endif

	{{-- Vouchers rendidos --}}

	@if(count($vouchers_rendidos))
	<h4>Vouchers rendidos</h4>
	<table class="table table-hover table-sm">
		<thead>
			<tr>
				<th>Nº Voucher</th>
				<th>Salida</th>
				<th>Detalle</th>
				<th>Total Real</th>
				<th>Desc. Antic.</th>
				<th>Recibido</th>
			</tr>
		</thead>
		<tbody>
			@foreach($vouchers_rendidos as $voucher)
			<tr>
				<td>{{$voucher->numero}}</td>
				@if($voucher->venta->salida)
				<td>{{date('d/m/Y', strtotime($voucher->venta->salida->fecha))}} - {{date('H:i', strtotime($voucher->venta->salida->hora))}}</td>
				@else
				<td>Sin datos de la Salida</td>
				@endif
				<td>{{$voucher->venta->detalle}}</td>
				<td>${{pesosargentinos($voucher->venta->total_real)}}</td>
				<td>${{pesosargentinos($voucher->venta->descuento)}} / ${{pesosargentinos($voucher->venta->anticipo)}}</td>
				<td><strong>${{pesosargentinos($voucher->venta->total)}}</strong></td>
			</tr>
			@endforeach
		</tbody>
	</table>
	@endif
</div>
@endsection
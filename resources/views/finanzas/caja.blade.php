@extends('layouts.app')
@section('content')
@section('titulo', 'Caja del dia - ')
@include('finanzas.modals.ingreso_create')
@include('finanzas.modals.retiro_create')
@include('finanzas.modals.ingreso_edit')
@include('finanzas.modals.retiro_edit')
<div class="container">
  <h3>
    Caja del día
    <span class="float-end">
      <a data-toggle="tooltip" title="Exportar a Excel" class="btn btn-success" href="{{route('download.caja.dia.excel')}}"><i class="bi bi-file-earmark-excel"></i></a>
      @canany(['administrar', 'ventas'])
      <button class="btn btn-success" data-toggle="tooltip" title="Ingreso extra" id="btn_ingreso_extra"><i class="bi bi-plus-lg"></i></button>
      <button class="btn btn-warning" data-toggle="tooltip" title="Retiro extra" id="btn_retiro_extra"><i class="bi-dash-lg"></i></button>
      @endcan
      @include('components.misc.backbutton', ['url' => url('home')])
    </span>
  </h3>
  <hr>
  <div class="row">
    <div class="col-12 col-md-4">

      <div class="card info h-100">
        <div class="card-header">
          <div class="h4">Ventas</div>
        </div>
        <div class="card-body">
          <div class="h3">
            Ventas: <strong>{{count($ventas)}}</strong>
          </div>
          <table class="table table-sm table-striped">
            @foreach($resumen_de_pagos as $medio)
            <tr>
              <td>{{ucwords($medio->nombre)}}</td>
              <td>{{$medio->simbolo}}{{pesosargentinos($medio->total)}}</td>
            </tr>
            @endforeach
          </table>
        </div>
      </div>
    </div>
  </div>

  @if(count($ventas) > 0)
  <div class="py-2"></div>
  <div class="h4">Resumen de ventas</div>
  <div class="table-responsive">    
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th>Hora</th>
          <th>DNI</th>
          <th>Pasajero</th>
          <th>Detalle</th>
          <th>Desc.</th>
          <th>Anti.</th>
          <th>Total</th>
          <th>Pagos</th>
        </tr>
      </thead>
      <tbody>
        @foreach($ventas as $venta)
        @if($venta->total > 0)
        <tr>
          <td>{{date('H:i', strtotime($venta->created_at))}}</td>
          <td>{{$venta->cuit}}</td>
          <td>{{ucwords($venta->razon_social)}}</td>
          <td>{{$venta->detalle}}</td>
          <td>${{pesosargentinos($venta->descuento)}}</td>
          <td>${{pesosargentinos($venta->anticipo)}}</td>
          <td>${{pesosargentinos($venta->total)}}</td>
          <td>
            @if(count($venta->abonos) > 1)
            Pago múltiple
            @elseif(count($venta->abonos) == 1)
            {{$venta->abonos[0]->medio_pago->nombre}}
            @else
            Sin pagos
            @endif
            <a href="{{route('venta.show', $venta)}}" data-toggle="tooltip" title="Ver detalle"><i class="bi bi-search"></i></a>
          </td>
        </tr>
        @endif
        @endforeach
      </tbody>
    </table>
  </div>
  @endif

  @if(count($vouchers) > 0)
  <div class="py-2"></div>
  <div class="h4">Vouchers</div>
  <div class="table-responsive">    
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th>#Voucher</th>
          <th>Agencia</th>
          <th>Cliente</th>
          <th>Paga</th>
          <th>Detalle</th>
          <th>Salida</th>
        </tr>
      </thead>
      <tbody>
        @foreach($vouchers as $voucher)
        <tr>
          <td>{{$voucher->numero}}</td>
          <td>
            @if($voucher->agencia)
            <a href="{{route('agencia.show', $voucher->agencia)}}">{{ucwords($voucher->agencia->razon_social)}}</a>
            @else
            <em class="text-muted">Agencia eliminada</em>
            @endif
          </td>
          <td>{{ucwords($voucher->venta->razon_social)}}</td>
          <td>${{pesosargentinos($voucher->venta->total)}}</td>
          <td>{{$voucher->venta->detalle}}</td>
          <td>@if($voucher->venta->salida) {{date('H:i', strtotime($voucher->venta->salida->hora))}} @else - @endif</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif

  @if(count($retiros) > 0)
  @include('components.finanzas.retiros')
  @endif

  @if(count($ingresos) > 0)
  @include('components.finanzas.ingresos')
  @endif


</div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready(function(){

    $('#btn_retiro_extra').click(function(){
      $('#mdl_retiro_create').modal('show');
    });
    $('#btn_ingreso_extra').click(function(){
      $('#mdl_ingreso_create').modal('show');
    });
  });
</script>
@endsection
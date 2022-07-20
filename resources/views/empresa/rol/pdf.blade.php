@extends('layouts.pdf')
@section('content')
<table class="table">
<tr>
  <td width="20%">
    <img width="120" src="{{asset('storage/img/'. variable_global('avatar'))}}">
  </td>
  <td width="80%">
    <p style="font-size: 12px;">Se AUTORIZA a la SALIDA de la embarcación: {{ucwords($rol->embarcacion->nombre)}} (matrícula: {{ucwords($rol->embarcacion->matricula)}}). Hora: {{date('H:i', strtotime($rol->hora))}}, del día {{date('d/m/Y', strtotime($rol->fecha))}} CON DESTINO a la Bahia local, realizando prácticas de AVISTAJE con el PATRÓN: 
  @if($rol->capitan){{strtoupper($rol->capitan->apellido)}} {{strtoupper($rol->capitan->nombre)}} LE/CE Nº ______________ @else ________________ @endif. 
  TRIPULACIÓN: @if($rol->tripulante) {{strtoupper($rol->tripulante->apellido)}} {{strtoupper($rol->tripulante->nombre)}} @else ________________ @endif.
  FOTOGRAFÍA: @if($rol->fotografo) {{strtoupper($rol->fotografo->apellido)}} {{strtoupper($rol->fotografo->nombre)}} @else ________________ @endif    -   (RES. PNA Nº           ):
  </td>
</tr>
  </table>


  <div>Pasajeros</div>
  <table class="table table-striped table-sm" style="font-size: 10px;" border="1">
    <thead class="head">
      <tr>
        <th>#</th>
        <th>Nombre</th>
        <th>DNI</th>
        <th>Nacionalidad</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rol->salida->pasajeros as $i => $pasajero)
      <tr>
        <td>{{$i+1}}</td>
        <td>{{ucwords($pasajero->apellido . ' ' . $pasajero->nombre)}}</td>
        <td>{{strtoupper($pasajero->dni)}}</td>
        <td>{{strtoupper($pasajero->pais->nombre)}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @endsection
@extends('layouts.app')
@section('content')
@section('titulo', 'Pasajeros - ')

<div class="container">
  <h3>
    Pasajeros de la salida: {{ fecha_string_completa($salida->fecha) }} a las: {{date('H:i', strtotime($salida->hora))}}
    <span class="float-end">
      @include('components.misc.backbutton', ['url' => route('salidas.index')])
    </span>
  </h3>
  <hr>

  @if(count($pasajeros) == 0)
  <p class="lead">Sin pasajeros</p>
  @else
  <div class="table-responsive">
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>DNI</th>
          <th>Nacionalidad</th>
        </tr>
      </thead>
      <tbody>
        @foreach($pasajeros as $i => $pasajero)
        <tr>
          <td>{{$i+1}}</td>
          <td>{{ucwords($pasajero->apellido . ' ' . $pasajero->nombre)}}</td>
          <td>{{strtoupper($pasajero->dni)}}</td>
          <td>{{strtoupper($pasajero->pais->nombre)}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
</div>
@endsection
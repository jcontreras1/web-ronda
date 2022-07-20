@extends('layouts.app')
@section('content')
@section('titulo', 'Rol - ')
@include('empresa.rol.modals.personal_edit')
@include('empresa.rol.modals.embarcacion_edit')
@include('empresa.rol.modals.fecha_hora_edit')
<div class="container">
  <h3>
    Rol de Embarque
    <span class="float-end">
      <a class="btn btn-primary" href="{{route('rol.pdf', $rol)}}" data-toggle="tooltip" title="Descargar Rol"><i class="bi bi-file-earmark-arrow-down-fill"></i></a>
      @include('components.misc.backbutton', ['url' => route('salida.index')])
    </span>
  </h3>
  <hr>

  <h4>Detalle</h4>
  <div class="card info">
    <div class="card-body">
      <div class="row">
        <div class="col-12 col-md-4">
          <table class="table table-sm">
            <tr>
              <td>Capitan</td>
              <td><strong>@if($rol->capitan){{$rol->capitan->nombre}} @else No definido @endif </strong></td>
              <td><button data-toggle="tooltip" title="Editar capitán" class="btn btn-warning btn-sm" id="btn_editar_capitan"><i class="bi bi-pencil-fill"></i></button></td>
            </tr>
            <tr>
              <td>Tripulante</td>
              <td><strong>@if($rol->tripulante){{$rol->tripulante->nombre}} @else No definido @endif </strong></td>
              <td><button data-toggle="tooltip" title="Editar tripulante" class="btn btn-warning btn-sm" id="btn_editar_tripulante"><i class="bi bi-pencil-fill"></i></button></td>
            </tr>
          </table>
        </div>
        <div class="col-12 col-md-4">
          <table class="table table-sm">
            <tr>
              <td>Fotografía</td>
              <td><strong>@if($rol->fotografo){{$rol->fotografo->nombre}} @else No definido @endif </strong></td>
              <td><button data-toggle="tooltip" title="Editar fotografía" class="btn btn-warning btn-sm" id="btn_editar_fotografia"><i class="bi bi-pencil-fill"></i></button></td>
            </tr> 
            <tr>
              <td>Embarcación</td>
              <td><strong>@if($rol->embarcacion){{$rol->embarcacion->nombre}} @else No definida @endif </strong></td>
              <td><button data-toggle="tooltip" title="Editar embarcación" class="btn btn-warning btn-sm" id="btn_editar_embarcacion"><i class="bi bi-pencil-fill"></i></button></td>
            </tr>
          </table>
        </div>
        <div class="col-12 col-md-4">
           <table class="table table-sm">
            <tr>
              <td>Fecha</td>
              <td><strong>{{date('d/m/Y', strtotime($rol->fecha))}}</strong></td>
              <td><button data-toggle="tooltip" title="Editar Fecha" class="btn btn-warning btn-sm btn_editar_fecha_hora"><i class="bi bi-pencil-fill"></i></button></td>
            </tr> 
            <tr>
              <td>Hora</td>
              <td><strong>{{date('H:i', strtotime($rol->hora))}}</strong></td>
              <td><button data-toggle="tooltip" title="Editar Hora" class="btn btn-warning btn-sm btn_editar_fecha_hora"><i class="bi bi-pencil-fill"></i></button></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <hr>

  <h4>Pasajeros | <strong>{{count($rol->salida->pasajeros)}}</strong></h4>
  @if(count($rol->salida->pasajeros) == 0)
  <p class="lead">Sin pasajeros en este rol</p>
  @else
  <div class="table-responsive">
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>DNI</th>
          <th>Nacionalidad</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($rol->salida->pasajeros as $i => $pasajero)
        <tr>
          <td>{{$i+1}}</td>
          <td>{{ucwords($pasajero->apellido . ' ' . $pasajero->nombre)}}</td>
          <td>{{strtoupper($pasajero->dni)}}</td>
          <td>{{strtoupper($pasajero->pais->nombre)}}</td>
          <td>
            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
</div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready(function(){
    var fotografos = `
    <select class="form-select" name="fotografo_id">
    <option value="">Sin Fotografo</option>
    @foreach($fotografos as $fotografo)
    <option value="{{$fotografo->id}}">{{$fotografo->apellido}} {{$fotografo->nombre}}</option>
    @endforeach
    </select>
    `;
    var capitanes = `
    <select class="form-select" name="capitan_id">
    @foreach($capitanes as $capitan)
    <option value="{{$capitan->id}}">{{$capitan->apellido}} {{$capitan->nombre}}</option>
    @endforeach
    </select>
    `;
    var tripulantes = `
    <select class="form-select" name="tripulante_id">
    @foreach($tripulantes as $tripulante)
    <option value="{{$tripulante->id}}">{{$tripulante->apellido}} {{$tripulante->nombre}}</option>
    @endforeach
    </select>
    `;

    $('#btn_editar_capitan').click(function(){
      $('#mdl_personal_edit').modal('show');
      $('.tipo_personal').html('Capitan');
      $('#select_personal').html(capitanes);
    });
    $('#btn_editar_tripulante').click(function(){
      $('#mdl_personal_edit').modal('show');
      $('.tipo_personal').html('Tripulante');
      $('#select_personal').html(tripulantes);
    }); 
    $('#btn_editar_fotografia').click(function(){
      $('#mdl_personal_edit').modal('show');
      $('.tipo_personal').html('Fotografía');
      $('#select_personal').html(fotografos);
    });    
    $('#btn_editar_embarcacion').click(function(){
      $('#mdl_embarcacion_edit').modal('show');
    });  
    $('.btn_editar_fecha_hora').click(function(){
      $('#mdl_fecha_hora_edit').modal('show');
    });
  });
</script>
@endsection
@extends('layouts.app')
@section('content')
@include('infraestructura.embarcacion.modals.embarcacion_create')
@section('titulo', 'Embarcaciones - ')
<div class="container">
  <h3>
    Embarcaciones
    <span class="float-end">
      <button class="btn btn-success" data-toggle="tooltip" title="Agregar Embarcacion" id="btn_embarcacion_create"><i class="bi bi-plus"></i></button>
      @include('components.misc.backbutton', ['url' => url('home')])
    </span>
  </h3>
  <div class="py-2"></div>
  <hr>
  <div class="table-responsive">    
    <table class="table table-hover" id="tabla">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Capacidad</th>
          @can('administrar')
          <th>Opciones</th>
          @endcan
        </tr>
      </thead>
      <tbody>
        @foreach ($embarcaciones as $embarcacion)
        @can('administrar')
        <tr class="row-click" data-url="{{route('embarcacion.edit', $embarcacion)}}">
          @else
          <tr>
          @endcan
          <td>{{ ucwords($embarcacion->nombre) }}</td>
          <td>{{ $embarcacion->capacidad }}</td>
          @can('administrar')
          <td>
            <a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Editar embarcación" href="{{route('embarcacion.edit', $embarcacion)}}"><i class="bi bi-pencil-fill"></i></a>
            <button class="btn btn-danger btn-sm eliminar_embarcacion" data-url="{{route('embarcacion.destroy', $embarcacion)}}" data-toggle="tooltip" title="Eliminar embarcación"><span class="bi bi-trash"></span></button>
          </td>
          @endcan
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@can('administrar')
<form id="form_delete_embarcacion" method="POST">@csrf @method('DELETE')</form>
@endcan
@endsection
@section('scripts')
<script type="text/javascript">
 $(document).ready(function(){
  $('.row-click td:not(:last-child)').click(function (e) {
    let url = $(this).parent().data('url');
    location.href = url;
  });
@can('administrar')
  $('.eliminar_embarcacion').click(function(){
    let url = $(this).data('url');
    Swal.fire({
      icon: 'question',
      title: 'Eliminar Marca',
      showCancelButton: true,
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result) => {
      if (result.isConfirmed) {
        $('#form_delete_embarcacion').attr('action', url);
        $('#form_delete_embarcacion').submit();
      }
    });
  });
@endcan
  $('#btn_embarcacion_create').click(function(){
    $('#mdl_embarcacion_create').modal('show');
  });
  $('#tabla').DataTable({
    language : {
      url : '{{asset('assets/dt.spanish.json')}}'
    }
  });
});
</script>
@endsection
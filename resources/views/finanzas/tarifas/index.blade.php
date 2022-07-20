@extends('layouts.app')
@section('content')
@section('titulo', 'Tarifas - ')
@include('finanzas.tarifas.modals.create')
@include('finanzas.tarifas.modals.edit')
<div class="container">
  <h3>
    Tarifas
    <span class="float-end">
      <button class="btn btn-success" data-toggle="tooltip" title="Agregar Tarifa" id="btn_agregar_tarifa"><i class="bi bi-plus"></i></button>
      @include('components.misc.backbutton', ['url' => url('home')])
    </span>
  </h3>
  <div class="py-2"></div>
  <hr>
  <div class="table-responsive">
    <table class=" table table-hover table-sm" id="tabla" data-page-length='25'>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Importe</th>
          <th>Opciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($tarifas as $tarifa)
        <tr>
          <td>{{ ucwords($tarifa->nombre) }} {{ucwords($tarifa->apellido)}}</td>
          <td>{{ $tarifa->importe }}</td>
          <td>
            <button class="btn btn-warning btn-sm btn_modif_tarifa" data-url="{{route('tarifa.update', $tarifa)}}" data-nombre="{{$tarifa->nombre}}" data-importe="{{$tarifa->importe}}" data-toggle="tooltip" title="Editar"><i class="bi bi-pencil-fill"></i></button>
            @can('administrar')
            @if($tarifa->id > 3)
            <button data-toggle="tooltip" title="Eliminar Tarifa" class="btn btn-danger btn_delete_tarifa btn-sm" data-url="{{route('tarifa.destroy', $tarifa)}}">
              <i class="bi bi-trash"></i>
            </button>
            @endif
            @endcan
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@can('administrar')
<form id="form_delete_tarifa" method="POST"> @csrf @method('DELETE') </form>
@endcan
@endsection

@section('scripts')
<script type="text/javascript">
 $(document).ready(function(){
  $('#tabla').DataTable({
    language : {
      url : '{{asset('assets/dt.spanish.json')}}'
    }
  });

  $('#btn_agregar_tarifa').click(function(){
    $('#mdl_tarifa_create').modal('show');
  });

  $('.btn_modif_tarifa').click(function(){
    $('#mdl_tarifa_edit').modal('show');
    let url = $(this).data('url');
    let nombre = $(this).data('nombre');
    let importe = $(this).data('importe');
    $('#form_tarifa_edit').attr('action', url);
    $('#mdl_tarifa_edit_nombre').val(nombre);
    $('#mdl_tarifa_edit_importe').val(importe);
  });
  @can('administrar')
  $('.btn_delete_tarifa').click(function(){
    let url = $(this).data('url');
    Swal.fire({
      icon: 'question',
      title: '¿Eliminar Tarifa?',
      showCancelButton: true,
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result) => {
      if (result.isConfirmed) {
        $('#form_delete_tarifa').attr('action', url);
        $('#form_delete_tarifa').submit();
      }
    })
  });
  @endcan
});
</script>
@endsection
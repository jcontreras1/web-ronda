@extends('layouts.app')
@section('content')
@section('titulo', 'Agencias - ')
@include('empresa.agencia.modals.create')
<div class="container">
  <h3>
    <i class="fas fa-users"></i> Agencias
    <span class="float-end">
      @canany(['administrar', 'ventas'])
      <button id="btn_agregar_agencia" class="btn btn-success text-white" data-toggle="tooltip" title="Agregar Agencia"><i class="bi bi-plus"></i></button>
      @endcan
      @include('components.misc.backbutton', ['url' => url('home')])
    </span>
  </h3>
  <div class="py-2"></div>
  <hr>
  <div class="table-responsive">
    <table class=" table table-hover table-sm" id="tabla" data-page-length='25'>
      <thead>
        <tr>
          <th>Razón social</th>
          <th>Correo Electrónico</th>
          <th>Cuit</th>
          <th>Teléfono</th>
          @canany(['administrar', 'ventas'])
          <th>Opciones</th>
          @endcan
        </tr>
      </thead>
      <tbody>
        @foreach ($agencias as $agencia)
        <tr>
          <td><a href="{{route('agencia.show', $agencia)}}">{{ ucwords($agencia->razon_social) }}</a></td>
          <td>{{ ucwords($agencia->email) }}</td>
          <td>{{ ucwords($agencia->cuit) }}</td>
          <td>{{ ucwords($agencia->telefono) }}</td>
          @canany(['administrar', 'ventas'])
          <td>
            <a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Editar" href="{{route('agencia.edit', $agencia)}}"><i class="bi bi-pencil-fill"></i></a>
            @can('administrar')
            <button data-toggle="tooltip" title="Eliminar Agencia" class="btn btn-danger btn_delete_agencia btn-sm" data-url="{{route('agencia.destroy', $agencia->id)}}">
              <i class="bi bi-trash"></i>
            </button>
            @endcan
          </td>
          @endcan
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@can('administrar')
<form id="form_delete_agencia" method="POST"> @csrf @method('DELETE') </form>
@endcan
@endsection

@section('scripts')
<script type="text/javascript">
 $(document).ready(function(){
  $('#btn_agregar_agencia').click(function(){
    $('#mdl_agencia_create').modal('show');
  });
  $('#tabla').DataTable({
    language : {
      url : '{{asset('assets/dt.spanish.json')}}'
    }
  });
  @can('administrar')
  $('.btn_delete_agencia').click(function(){
    let url = $(this).data('url');
    Swal.fire({
      icon: 'question',
      title: '¿Eliminar Empresa?',
      showCancelButton: true,
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result) => {
      if (result.isConfirmed) {
        console.log(url);
        $('#form_delete_agencia').attr('action', url);
        $('#form_delete_agencia').submit();
      }
    })
  });
  @endcan
});



</script>
@endsection
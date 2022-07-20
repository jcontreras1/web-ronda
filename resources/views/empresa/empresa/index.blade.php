@extends('layouts.app')
@section('content')

<div class="container">
  <h3>
    Empresas
    <span class="float-end">
      <a href="{{route('empresa.create')}}" class="btn btn-success text-white" data-toggle="tooltip" title="Agregar Empresa"><i class="bi bi-plus"></i></a>
      @include('components.generales.backbutton', ['url' => url('home')])
    </span>
  </h3>
  <div class="py-2"></div>
  <hr>
  <div class="table-responsive">    
    <table class="table table-hover table-sm" id="tabla">
      <thead>
        <tr>
          <th>Razón Social</th>
          <th>Cuit</th>
          <th>Correo Electrónico</th>
          <th>Opciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($empresas as $empresa)
        <tr class="row-click" data-url="{{route('empresa.show', $empresa)}}">
          <td>{{ ucwords($empresa->razon_social) }}</td>
          <td>{{ $empresa->cuit }}</td>
          <td>{{ $empresa->email }}</td>
          <td>
            <a class="btn btn-primary btn-sm" data-toggle="tooltip" title="Detalles" href="{{route('empresa.show', $empresa)}}"><i class="bi bi-list-check"></i></a>
            <button data-toggle="tooltip" title="Eliminar Empresa" class="btn btn-danger btn_delete_empresa btn-sm" data-url="{{route('empresa.destroy', $empresa)}}">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
{{-- @can('administrar_sistema') --}}
<form id="form_delete_empresa" method="POST"> @csrf @method('DELETE') </form>
{{-- @endcan --}}
@endsection

@section('scripts')
<script type="text/javascript">
 $(document).ready(function(){
  $('.row-click td:not(:last-child)').click(function ()    {
    let url = $(this).parent().data('url');
    location.href = url;
  });
  $('#tabla').DataTable({
    language : {
      url : '{{asset('assets/dt.spanish.json')}}'
    }
  });
  $('.btn_delete_empresa').click(function(){
    let url = $(this).data('url');
    Swal.fire({
      icon: 'question',
      title: 'Eliminar Empresa',
      showCancelButton: true,
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        console.log(url);
        $('#form_delete_empresa').attr('action', url);
        $('#form_delete_empresa').submit();
      }
    });
  });
});
</script>
@endsection
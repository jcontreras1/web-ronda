@extends('layouts.app')
@section('content')
@include('infraestructura.marcas.modals.marca_create')
<div class="container">
  <h3>
    Marcas
    <span class="float-end">
      <button class="btn btn-success" data-toggle="tooltip" title="Agregar Marca" id="btn_marca_create"><i class="bi bi-plus"></i></button>
      @include('components.generales.backbutton', ['url' => url('home')])
    </span>
  </h3>
  <div class="py-2"></div>
  <hr>
  <div class="table-responsive">    
    <table class="table table-hover" id="tabla">
      <thead>
        <tr>
          <th>Marca</th>
          <th>Modelos</th>
          <th>Opciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($marcas as $marca)
        <tr class="row-click" data-url="{{route('marca.show', $marca)}}">
          <td>{{ ucwords($marca->nombre) }}</td>
          <td><span data-toggle="tooltip" title="@foreach($marca->modelos as $modelo) {{ucfirst($modelo->nombre)}} @endforeach">{{count($marca->modelos)}} @if(count($marca->modelos) !== 1) modelos @else modelo @endif</span></td>
          <td>
            <a class="btn btn-primary btn-sm" data-toggle="tooltip" title="Panel de marca" href="{{route('marca.show', $marca)}}"><i class="bi bi-list-task"></i></a>
            @if(count($marca->modelos) == 0)
            <button class="btn btn-danger btn-sm eliminar_marca" data-url="{{route('marca.destroy', $marca)}}" data-toggle="tooltip" title="Eliminar marca"><span class="bi bi-trash"></span></button>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<form id="form_delete_marca" method="POST">@csrf @method('DELETE')</form>
@endsection

@section('scripts')
<script type="text/javascript">
 $(document).ready(function(){
  $('.row-click td:not(:last-child)').click(function (e) {
    let url = $(this).parent().data('url');
    location.href = url;
  });
  $('.eliminar_marca').click(function(){
    let url = $(this).data('url');
    Swal.fire({
      icon: 'question',
      title: 'Eliminar Marca',
      showCancelButton: true,
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result) => {
      if (result.isConfirmed) {
        $('#form_delete_marca').attr('action', url);
        $('#form_delete_marca').submit();
      }
    });
  });
  $('#btn_marca_create').click(function(){
    $('#mdl_marca_create').modal('show');
  });
  $('#tabla').DataTable({
    language : {
      url : '{{asset('assets/dt.spanish.json')}}'
    }
  });
});
</script>
@endsection
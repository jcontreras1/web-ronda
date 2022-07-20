@extends('layouts.app')
@section('content')
@include('infraestructura.marcas.modals.modelo_edit')
@include('infraestructura.marcas.modals.modelo_create', ['marca' => $marca])
<div class="container">
  <h3>
    <strong>{{ucfirst($marca->nombre)}}</strong>
    <span class="float-end">
      <a class="btn btn-success text-white" data-toggle="tooltip" title="Agregar Modelo" id="agregar_modelo"><i class="bi bi-plus"></i></a>
      @include('components.generales.backbutton', ['url' => route('marca.index')])
    </span>
  </h3>
  <hr>
  <form action="{{route('marca.update', $marca)}}" method="post">
    @csrf @method('PATCH')
    <label>Editar esta marca <i class="bi bi-pencil-fill text-primary"></i></label>
    <div class="input-group">
      <input autocomplete="off" type="text" name="nombre" value="{{$marca->nombre}}" class="form-control @if($errors->has('nombre')) is-invalid @endif" placeholder="Nombre de la Marca" value="{{$marca->nombre}}">
      <button class="btn btn-outline-success">Guardar</button>
    </div>
  </form>
  <div class="py-1"></div>
  <div class="row">
    @foreach($modelos as $modelo)
    @include('components.infraestructura.card_modelo', ['modelo' => $modelo])
    @endforeach
  </div>
</div>
<form id="form_delete_modelo" method="POST">@csrf @method('DELETE')</form>
@endsection

@section('scripts')

<script type="text/javascript">
  $(document).ready(function(){
    /*Este modulo está en components.infraestructura.card_modelo*/
    $('.edit_modelo').click(function(){
      $('#mdl_modelo_edit').modal('show');
      $('#form_modelo_edit').attr('action', $(this).data('url'));
      $('#nombre_modelo').val($(this).data('nombre'));
    });

    $('#agregar_modelo').click(function(){
      $('#mdl_modelo_create').modal('show');
    });

    $('.eliminar_modelo').click(function(){
      let url = $(this).data('url');
      Swal.fire({
        icon: 'question',
        title: 'Eliminar Modelo',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',
      }).then((result) => {
        if (result.isConfirmed) {
          $('#form_delete_modelo').attr('action', url);
          $('#form_delete_modelo').submit();
        }
      });
    });
  });
</script>

@endsection
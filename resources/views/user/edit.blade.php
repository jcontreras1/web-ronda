@extends('layouts.app')
@section('content')
@include('user.modals.user_cargo_create')
@section('titulo', 'Editar Usuario - ')
<div class="container">
    <h3>
        Editar Usuario
        <span class="float-end">
            @include('components.misc.backbutton', ['url' => route('user.index')])
        </span>
    </h3>
    <hr>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <form method="post" action="{{route('user.update', $user->id)}}">
                @method('PATCH')
                @csrf
                <div class="row">
                    <div class="col-12 col-md-6">
                        <strong>Email @include('components.misc.required')</strong>
                        <input type="email" autofocus required value="{{$user->email}}" name="email" class="form-control @if($errors->has('email')) is-invalid @endif" placeholder="Email">
                    </div>
                    <div class="col-12 col-md-6">
                        <strong>Nombre @include('components.misc.required')</strong>
                        <input type="text" value="{{$user->nombre}}" name="nombre" class="form-control @if($errors->has('nombre')) is-invalid @endif" placeholder="Nombre" autocomplete="off" >
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <strong>Apellido</strong>
                        <input type="text" value="{{$user->apellido}}" name="apellido" class="form-control" placeholder="Apellido">
                    </div>
                    <div class="col-12 col-md-6">
                        <strong>DNI</strong>
                        <input type="text" value="{{$user->dni}}" name="dni" class="form-control @if($errors->has('dni')) is-invalid @endif" placeholder="DNI">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <strong>Teléfono celular</strong>
                        <input type="text" value="{{$user->telefono}}" name="telefono" class="form-control" placeholder="Teléfono">
                    </div>
                    <div class="col-12 col-md-6">
                        <strong>Fecha de Nacimiento</strong>
                        <input type="date" value="{{$user->fecha_nacimiento ?? date('Y-m-d') }}" name="fecha_nacimiento" class="form-control" >
                    </div>
                </div>
                <div class="py-2"></div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xs-12 col-md-6">
            <h5>Cargo 
                @can('administrar')
                | 
                <button type="button" class="btn btn-success btn-sm" id="agregar_cargo" data-toggle="tooltip" title="Agregar/Cambiar cargo"><i class="bi bi-plus"></i></button>
                @endcan
            </h5>
            <hr>
            @if(count($user->tipos_usuario) == 0)
            <small>Sin cargos</small>
            @else
            <table class="table table-striped table-sm">
                <tbody>
                    @foreach($user->tipos_usuario as $tipo_usuario)
                    <tr>
                        <td>{{$tipo_usuario->nombre}}</td>
                        @can('administrar')
                        <td class=""><button type="button" data-toggle="tooltip" title="Eliminar cargo" data-url="{{route('cargo.destroy', ['user' => $user, 'cargo' => $tipo_usuario->pivot->id])}}" class="btn btn-danger btn-sm btn_delete_cargo" ><i class="bi bi-trash"></i></button></td>
                        @endcan
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div> 
    </div>
</div>
<form id="form_delete_cargo" method="POST">@csrf @method('DELETE') </form>
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){       
        $('#agregar_cargo').click(function(){
            $('#mdl_cargo_add').modal('show');
        });
        $('.btn_delete_cargo').click(function(){
            let url = $(this).data('url');
            $(this).blur();
            Swal.fire({
              title: '¿Eliminar el cargo del usuario?',
              icon: 'question',
              showCancelButton: true,
              confirmButtonText: 'Si',
              cancelButtonText: 'No'
          }).then((result) => {
              if (result.isConfirmed) {
                $('#form_delete_cargo').attr('action', url);
                $('#form_delete_cargo').submit();
            }
        });
      });
    });
</script>
@endsection



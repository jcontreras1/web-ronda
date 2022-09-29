@extends('layouts.app')
@section('content')
@include('user.modals.user_cargo_create')
@include('user.modals.user_area_create')
@section('titulo', 'Editar Usuario - ')
<div class="container">
    <x-misc-title title="Editar Usuario" back="{{ route('user.index') }}" />
    <div class="row">
        <div class="col-xs-12 col-md-6 mb-3">
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
                        <td>
                            <form method="POST" action="{{route('cargo.destroy', ['user' => $user, 'cargo' => $tipo_usuario->pivot->id])}}">
                                @csrf
                                @method('DELETE')
                                <button data-toggle="tooltip" title="Eliminar cargo" class="btn btn-danger btn-sm" ><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                        @endcan
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            <div class="py-2"></div>
            <h5>Áreas 
                @can('supervisar')
                | 
                <button type="button" class="btn btn-success btn-sm" id="agregar_area" data-toggle="tooltip" title="Agregar/Cambiar area"><i class="bi bi-plus"></i></button>
                @endcan
            </h5>
            <hr>
            @if(count($user->areas) == 0)
            <small>Sin Áreas</small>
            @else
            <table class="table table-striped table-sm">
                <tbody>
                    @foreach($user->areas as $area)
                    <tr>
                        <td>{{ strtoupper($area->nombre) }} @if($area->pivot->es_jefe) <i data-toggle="tooltip" title="Es jefe área" class="bi bi-star-fill text-warning"></i> @endif
                        @if(soy_jefe($area, Auth::user()))
                        <span class="float-end">
                            <form method="POST" action="{{route('area_usuario.destroy', ['user' => $user, 'area_usuario' => $area->pivot->id])}}">
                                @csrf @method('DELETE')
                                <button data-toggle="tooltip" title="Eliminar cargo" class="btn btn-danger btn-sm btn_delete_area" ><i class="bi bi-trash"></i></button>
                            </form>
                        </span>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

        </div> 
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">

    var btn_agregar_area = document.getElementById('agregar_area');
    btn_agregar_area.addEventListener('click', agregar_area);

    function agregar_area(){
        var modal = new bootstrap.Modal(document.getElementById('mdl_area_add'));
        modal.show();
    }

    $(document).ready(function(){       
        $('#agregar_cargo').click(function(){
            $('#mdl_cargo_add').modal('show');
        });
    });
</script>
@endsection



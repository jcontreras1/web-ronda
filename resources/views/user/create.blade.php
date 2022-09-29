@extends('layouts.app')
@section('titulo', 'Crear Usuario - ')
@section('content')
<div class="container">
    <x-misc-title title="Crear Usuario" back="{{ route('user.index') }}" />
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form action="{{route('user.store')}}" method="post" id="form">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-6">
                        <strong>Email @include('components.misc.required')</strong>
                        <input type="email" autofocus required value="{{old('email')}}" name="email" class="form-control @if($errors->has('email')) is-invalid @endif" placeholder="Email">
                    </div>
                    <div class="col-12 col-md-6">
                        <strong>Nombre @include('components.misc.required')</strong>
                        <input type="text" value="{{old('nombre')}}" name="nombre" class="form-control @if($errors->has('nombre')) is-invalid @endif" placeholder="Nombre" autocomplete="off" >
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <strong>Apellido</strong>
                        <input type="text" value="{{old('apellido')}}" name="apellido" class="form-control" placeholder="Apellido">
                    </div>
                    <div class="col-12 col-md-6">
                        <strong>DNI</strong>
                        <input type="text" value="{{old('dni')}}" name="dni" class="form-control @if($errors->has('dni')) is-invalid @endif" placeholder="DNI">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <strong>Teléfono celular</strong>
                        <input type="text" value="{{old('telefono')}}" name="telefono" class="form-control" placeholder="Teléfono Celular">
                    </div>
                    <div class="col-12 col-md-6">
                        <strong>Fecha de Nacimiento</strong>
                        <input type="date" value="{{ old('fecha_nacimiento') ?? date('Y-m-d') }}" name="fecha_nacimiento" class="form-control" >
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong>Rol</strong>
                        <select name="rol" class="form-select" required>
                            @foreach($tipos_usuario as $rol)
                            <option value="{{$rol->id}}">{{ $rol->nombre }} - {{$rol->descripcion}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <strong>Áreas</strong>
                        <select name="area[]" class="form-select" required multiple>
                            @foreach($areas as $area)
                            <option value="{{$area->id}}">{{ strtoupper($area->nombre) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>
                <div class="float-right">
                    <button type="submit" class="btn btn-success" id="btn_submit">Registrar</button>                        
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#form').submit(function(){
            $('#btn_submit').html(`<div class="spinner-border spinner-border-sm text-light" role="status"><span class="visually-hidden">Loading...</span></div> Enviando correo de bienvenida`);
            $('#btn_submit').attr('disabled', true);
        });
    });
</script>
@endpush
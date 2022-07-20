@extends('layouts.app')
@section('content')
<div class="container">
    <h3> 
        Editar Empresa
        <span class="float-end">
            @include('components.generales.backbutton', ['url' => route('empresa.show', $empresa)])
        </span>
    </h3>
    <hr> 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form action="{{route('empresa.update', $empresa)}}" method="post">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-12">
                        <strong>Razon Social @include('components.generales.required')</strong>
                        <input type="text" value="{{$empresa->razon_social}}" required name="razon_social" class="form-control @if($errors->has('razon_social')) is-invalid @endif" placeholder="Razon Social">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong>Cuit @include('components.generales.required')</strong>
                        <input type="text" value="{{$empresa->cuit}}" required name="cuit" class="form-control @if($errors->has('cuit')) is-invalid @endif" placeholder="Cuit">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong>Email @include('components.generales.required')</strong>
                        <input type="email" value="{{$empresa->email}}" required name="email" class="form-control @if($errors->has('email')) is-invalid @endif" placeholder="Email">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong>Domicilio</strong>
                        <input type="text" value="{{$empresa->domicilio}}" name="domicilio" class="form-control" placeholder="Domicilio">
                    </div>
                </div>                
                <div class="row">
                    <div class="col-12">
                        <strong>Telefono</strong>
                        <input type="text" value="{{$empresa->telefono}}" name="telefono" class="form-control" placeholder="Celular/Fijo">
                    </div>
                </div>
                <hr>
                <div class="float-right">
                    <button type="submit" class="btn btn-success">Guardar</button>                        
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
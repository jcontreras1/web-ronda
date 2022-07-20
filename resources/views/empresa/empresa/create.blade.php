@extends('layouts.app')
@section('content')
<div class="container">
    <h3> 
        Crear Empresa
        <span class="float-end">
            @include('components.generales.backbutton', ['url' => route('empresa.index')])
        </span>
    </h3>
    <hr> 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form action="{{route('empresa.store')}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <strong>Razon Social @include('components.generales.required')</strong>
                        <input type="text" value="{{old('razon_social')}}" required name="razon_social" class="form-control @if($errors->has('razon_social')) is-invalid @endif" placeholder="Razon Social">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong>Cuit @include('components.generales.required')</strong>
                        <input type="text" value="{{old('cuit')}}" required name="cuit" class="form-control @if($errors->has('cuit')) is-invalid @endif" placeholder="Cuit">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong>Email @include('components.generales.required')</strong>
                        <input type="email" value="{{old('email')}}" required name="email" class="form-control @if($errors->has('email')) is-invalid @endif" placeholder="Email">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong>Domicilio</strong>
                        <input type="text" value="{{old('domicilio')}}" name="domicilio" class="form-control" placeholder="Domicilio">
                    </div>
                </div>                
                <div class="row">
                    <div class="col-12">
                        <strong>Telefono</strong>
                        <input type="text" value="{{old('telefono')}}" name="telefono" class="form-control" placeholder="Celular/Fijo">
                    </div>
                </div>
                <hr>
                <div class="float-right">
                    <button type="submit" class="btn btn-success">Registrar</button>                        
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
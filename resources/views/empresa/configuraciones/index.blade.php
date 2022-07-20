@extends('layouts.app')
@section('content')
@section('titulo', 'Configuraciones - ')
<div class="container">
  <h3>
    Configuraciones
    <span class="float-end">
      @include('components.misc.backbutton', ['url' => url('home')])
    </span>
  </h3>
  <hr>
  <div class="row">

    {{-- Embarcación por defecto --}}
    <div class="col-12 col-md-3">
      <div class="card info h-100">
        <div class="card-body">
          <div class="display-2"><i class="bi bi-cursor-fill text-muted"></i></div>
          <h6 class="card-title">Embarcación por defecto al crear una salida</h6>
          <form method="POST" action="{{route('config.update', ['config' => $embarcacion_d->id])}}">
            @csrf
            @method('PATCH')
            <select class="form-select" name="valor">
              @foreach($embarcaciones as $embarcacion)
              @if($embarcacion->id == $embarcacion_d->valor)
              <option selected value="{{$embarcacion->id}}">{{ucfirst($embarcacion->nombre)}}</option>
              @else
              <option value="{{$embarcacion->id}}">{{ucfirst($embarcacion->nombre)}}</option>
              @endif
              @endforeach
            </select>
            <hr>
            <button class="btn btn-success">Guardar</button>
          </form>
        </div>
      </div>
    </div>
    
    {{-- Pais por defecto --}}
    <div class="col-12 col-md-3">
      <div class="card info h-100">
        <div class="card-body">
          <div class="display-2"><i class="bi bi-geo-alt text-muted"></i></div>
          <h6 class="card-title">País por defecto al agregar pasajeros a una venta</h6>
          <form method="POST" action="{{route('config.update', ['config' => $pais_d->id])}}">
            @csrf
            @method('PATCH')
            <select class="form-select" name="valor">
              @foreach($paises as $pais)
              @if($pais->id == $pais_d->valor)
              <option selected value="{{$pais->id}}">{{ucfirst($pais->nombre)}}</option>
              @else
              <option value="{{$pais->id}}">{{ucfirst($pais->nombre)}}</option>
              @endif
              @endforeach
            </select>
            <hr>
            <button class="btn btn-success">Guardar</button>
          </form>
        </div>
      </div>
    </div>

    {{-- Colores --}}
    <div class="col-12 col-md-3">
      <div class="card info h-100">
        <div class="card-body">
          <div class="display-2"><i class="bi bi-palette text-muted"></i></div>
          <h6 class="card-title">Se usan colores para las salidas</h6>
          <form method="POST" action="{{route('config.update', ['config' => $color_d->id])}}">
            @csrf
            @method('PATCH')
            <select class="form-select" name="valor">
              <option value="1" @if($color_d->valor == 1) selected @endif>SI</option>
              <option value="0" @if($color_d->valor == 0) selected @endif>NO</option>
            </select>
            <hr>
            <button class="btn btn-success">Guardar</button>
            @if(variable_global('USAR_COLORES_SALIDA') == 1)
            <a href="{{route('color.index')}}" class="btn btn-warning">Colores</a>
            @endif
          </form>
        </div>
      </div>
    </div>    

    {{-- Avatar de la Empresa --}}
    <div class="col-12 col-md-3">
      <div class="card info h-100">
        <div class="card-body">
          <img class="img-fluid img-thumbnail" width="150" src="{{asset('storage/img/'. variable_global('avatar'))}}">
          <div class="py-1"></div>
          <h6 class="card-title">Imagen para el membrete del rol de embarque, y otros posibles documentos.</h6>
          <div class="py-1"></div>
          <form method="POST" action="{{route('config.avatar')}}" enctype="multipart/form-data">
            @csrf
           <input type="file" class="form-control form-control-sm" name="avatar" accept="image/jpeg image/png" />
            <hr>
            <button class="btn btn-success">Guardar</button>
          </form>
        </div>
      </div>
    </div>

    {{-- Tarifario --}}
    <div class="col-12 col-md-3">
      <div class="card info h-100">
        <div class="card-body">
          <div class="display-1"><i class="bi bi-cash-coin text-muted"></i></div>
          <h5 class="card-title">Cambiar las tarifas de las salidas</h5>
          <hr>
          <div class="py-2"></div>
          <a class="btn btn-success" href="{{route('tarifa.index')}}">Cambiar</a>
        </div>
      </div>
    </div>


  </div>
</div>
@endsection
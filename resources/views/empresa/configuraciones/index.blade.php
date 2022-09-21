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

    {{-- Avatar de la Empresa --}}
    {{-- <div class="col-12 col-md-3">
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
    </div> --}}

    <div class="col-12 col-md-4 mb-3">
      <div class="card h-100 mb-4 shadow">
        <div class="card-body">
          <h3>Url Tejas</h3>
          <hr>
          <p class="text-muted">Url donde buscar las tejas en los mapas</p>
          <form method="POST" action="{{route('config.update', ['config' => obj_variable_global('URL_TILES')->id])}}" enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <input type="hidden" name="clave" value="URL_TILES">
            <div class="row">
              <div class="col-12">                
                <textarea required name="valor" rows="5" class="form-control">{{variable_global('URL_TILES')}}</textarea>
              </div>
            </div>
            <div class="py-3"></div>
            <div class="row">
              <div class="col-12">
                <button class="btn btn-success">Actualizar</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-4 mb-3">
      <div class="card h-100 mb-4 shadow">
        <div class="card-body">
          <h3>Api Token - Tejas</h3>
          <hr>
          <p class="text-muted">Api token para ver los mapas (Si el mapa es premium). Se debe dejar vacío en caso de un mapa libre</p>
          <form method="POST" action="{{route('config.update', ['config' => obj_variable_global('API_TOKEN_MAPS')->id])}}" enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <input type="hidden" name="clave" value="API_TOKEN_MAPS">
            <div class="row">
              <div class="col-12">                
                <textarea name="valor" rows="4" class="form-control">{{variable_global('API_TOKEN_MAPS')}}</textarea>
              </div>
            </div>
            <div class="py-3"></div>
            <div class="row">
              <div class="col-12">
                <button class="btn btn-success">Actualizar</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>


  </div>
</div>
@endsection
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


  </div>
</div>
@endsection
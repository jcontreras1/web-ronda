@extends('layouts.app')
@section('content')

<div class="container">
  <h3>
    <strong>{{ucwords($empresa->razon_social)}}</strong>
    <span class="float-end">
      <a class="btn btn-warning" data-toggle="tooltip" title="Editar" href="{{route('empresa.edit', $empresa)}}"><i class="bi bi-pencil-fill"></i></a>
      @include('components.generales.backbutton', ['url' => route('empresa.index')])
    </span>
  </h3>
  <hr>
  @can('gestionar_empresa', $empresa)

  <ul class="nav nav-pills" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#salidas" id="btn_salidas" type="button" role="tab" aria-selected="true">Salidas</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" data-bs-toggle="pill" data-bs-target="#personal" id="btn_personal" type="button" role="tab" aria-selected="false">Personal</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" data-bs-toggle="pill" data-bs-target="#vehiculos" id="btn_vehiculos" type="button" role="tab" aria-selected="false">Vehículos</button>
    </li>
  </ul>
  <div class="py-2"></div>
  
  <div class="tab-content">
    <div class="tab-pane active" id="salidas" role="tabpanel" aria-labelledby="salidas-tab">@include('components.empresa.panel_salidas')</div>
    <div class="tab-pane" id="personal" role="tabpanel">@include('components.empresa.panel_personal')</div>
    <div class="tab-pane" id="vehiculos" role="tabpanel">@include('components.empresa.panel_vehiculos')</div>
  </div>

  @endcan
</div>

@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready(function(){
    var hash = location.hash.replace(/^#/, ''); 
    if (hash) {
      $('#btn_'+hash).click();
    } 
  });
</script>
@endsection
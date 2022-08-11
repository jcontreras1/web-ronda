@extends('layouts.app')
@section('content')
@include('rondas.modals.comparar')
@section('titulo', 'Rondas - ')
<div class="container">
  <h3>
    <i class="fas fa-users"></i> Rondas abiertas
    <span class="float-end">
      <form method="post" action="{{route('ronda.store')}}">
        @csrf
        <button class="btn btn-success text-white" data-toggle="tooltip" title="Agregar ronda"><i class="bi bi-plus"></i></button>
        @include('components.misc.backbutton', ['url' => url('home')])
      </form>
    </span>
  </h3>
  <hr>

  <div class="row">
    @foreach($abiertas as $ronda)
    @if($ronda->abierta)
    <div class="col-12 col-md-4">
      <div class="card card-primary">
        <div class="card-header">
          <span class="float-end">
            <button data-toggle="tooltip" title="Cerrar ronda" class="btn btn-warning btn_cerrar_ronda" data-url="{{route('ronda.cerrar', $ronda)}}"><i class="bi bi-check2"></i></button>
            <button data-toggle="tooltip" title="Eliminar ronda" class="btn btn-danger btn_delete_ronda" data-url="{{route('ronda.destroy', $ronda)}}"><i class="bi bi-trash"></i></button>
          </span>
        </div>
        <div class="card-body">
          <a class="text-dark" href="{{route('ronda.show', $ronda)}}" style="text-decoration: none;">
            <div class="card-title">
              <h5>Ronda #{{$ronda->id}}</h5>
              <hr>
              @if(count($ronda->checkpoints) == 0)
              <small><em>Sin datos</em></small>
              @else
              {{count($ronda->checkpoints)}} puntos de control
              @endif
            </div>
          </a>
        </div>
        <div class="card-footer">
          Ronda creada {{$ronda->created_at->diffForHumans()}}
        </div>
      </div>
    </div>
    @endif
    @endforeach
  </div>
  <hr>
  <div class="row">
    <div class="col-12">
      <table class="table table-striped" id="tabla">
        <thead>
          <tr>
            <th>id</th>
            <th>Puntos</th>
            <th>Fecha</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cerradas as $ronda)
          <tr>
            <td>{{$ronda->id}}</td>
            <td>{{count($ronda->checkpoints)}}</td>
            <td>{{date('d/m/Y', strtotime($ronda->created_at))}}</td>
            <td>
              <a href="{{route('ronda.show', $ronda)}}" data-toggle="tooltip" title="Ver" class="btn btn-primary"><i class="bi bi-list-task"></i></a>
              @can('administrar')
              <button data-toggle="tooltip" title="Comparar" class="btn btn-primary" onclick="comparar({{$ronda->id}})"><i class="bi bi-map"></i></button>
              @endcan
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>  
{{-- @can('administrar_sistema') --}}
<form id="form_delete_ronda" method="POST"> @csrf @method('DELETE') </form>
<form id="form_cerrar_ronda" method="POST"> @csrf @method('PATCH') </form>
{{-- @endcan --}}
@endsection

@section('scripts')
<script type="text/javascript">
  var url_base = "{{route('ronda.comparar', ['ronda' => '__ronda', 'circuito' => '__circuito'])}}";
  $(document).ready(function(){
    $('#tabla').DataTable({
      language : {
        url : '{{asset('assets/dt.spanish.json')}}'
      }
    });
    $('.btn_delete_ronda').click(function(){
      let url = $(this).data('url');
      Swal.fire({
        icon: 'question',
        title: '¿Eliminar Ronda?',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'Cancelar',
      }).then((result) => {
        if (result.isConfirmed) {
          $('#form_delete_ronda').attr('action', url);
          $('#form_delete_ronda').submit();
        }
      })
    });
    $('.btn_cerrar_ronda').click(function(){
      let url = $(this).data('url');
      Swal.fire({
        icon: 'question',
        title: '¿Cerrar Ronda?',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'Cancelar',
      }).then((result) => {
        if (result.isConfirmed) {
          $('#form_cerrar_ronda').attr('action', url);
          $('#form_cerrar_ronda').submit();
        }
      })
    });
  });

  function comparar(ronda){
    document.getElementById('ronda_id').value = ronda;
    let url = url_base.replace('__ronda', ronda);
    let circ = $('#select_circuito').val();
    if(!circ){
      Swal.fire({
        icon: 'error',
        title: 'No hay circuitos definidos',
      });
      return;
    }else{
      $('#mdl_ronda_comparar').modal('show');
      url = url.replace('__circuito', circ);
      document.getElementById('btn_url_comparar').setAttribute('href', url);
    }
  }

  function build_url(circuito){
    ronda = document.getElementById('ronda_id').value;
    let url = url_base.replace('__ronda', ronda);
    url = url.replace('__circuito', circuito);
    document.getElementById('btn_url_comparar').setAttribute('href', url);
    
  }

</script>
@endsection
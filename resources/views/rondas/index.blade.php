@extends('layouts.app')
@section('content')
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
    @foreach($rondas as $ronda)
    <div class="col-12 col-md-4">
    <div class="card card-primary">
      <div class="card-header">
        <span class="float-end">
          <a href="#" data-toggle="tooltip" title="Cerrar ronda" class="btn btn-warning"><i class="bi bi-check2"></i></a>
          <a href="#" data-toggle="tooltip" title="Eliminar ronda" class="btn btn-danger"><i class="bi bi-trash"></i></a>
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
          @endif
        </div>
        </a>
      </div>
          <div class="card-footer">
            Ronda creada {{$ronda->created_at->diffForHumans()}}
          </div>
    </div>
    </div>
    @endforeach
  </div>


</div>  
{{-- @can('administrar_sistema') --}}
<form id="form_delete_user" method="POST"> @csrf @method('DELETE') </form>
{{-- @endcan --}}
@endsection

@section('scripts')
<script type="text/javascript">
 $(document).ready(function(){
  $('#tabla').DataTable({
    language : {
      url : '{{asset('assets/dt.spanish.json')}}'
    }
  });
  $('.btn_delete_user').click(function(){
    let url = $(this).data('url');
    Swal.fire({
      icon: 'question',
      title: 'Eliminar Usuario',
      showCancelButton: true,
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        console.log(url);
        $('#form_delete_user').attr('action', url);
        $('#form_delete_user').submit();
      }
    })
  });
});



</script>
@endsection
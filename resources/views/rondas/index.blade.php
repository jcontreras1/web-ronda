@extends('layouts.app')
@section('content')
{{-- @include('rondas.modals.comparar') --}}
@include('rondas.modals.create')
@section('titulo', 'Rondas - ')
<div class="container">
  <h3 class="">Rondas abiertas
    <span class="float-end">
      <button class="btn btn-success text-white" id="btn_ronda_create" data-toggle="tooltip" title="Agregar ronda"><i class="bi bi-plus"></i></button>
      @include('components.misc.backbutton', ['url' => url('home')])
    </span>
  </h3>
  <hr>
  {{-- Rondas abiertas --}}
  <div class="row mb-3">
    @foreach($abiertas as $ronda)
    
    @include('components.ronda.card-ronda-abierta', ['ronda' => $ronda])
    @endforeach
  </div>

  {{-- Rondas históricas --}}
  <h4>Histórico</h4>
  <div class="table-responsive">
    <table class="table table-striped" id="tabla">
      <thead>
        <tr>
          <th>Recorre</th>
          <th>Fecha</th>
          <th>Opciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($cerradas as $ronda)
        <tr onclick="ver_circuito('{{ route('ronda.show', $ronda) }}')" style="cursor: pointer;">
          <td data-order="{{ $ronda->id }}">{{ucwords($ronda->creador->nombre)}}</td>
          {{-- <td >{{count($ronda->checkpoints)}}</td> --}}
          <td data-order="{{ $ronda->id }}">{{date('d/m/Y H:i', strtotime($ronda->created_at))}}</td>
          <td>
            <a href="{{route('ronda.show', $ronda)}}" data-toggle="tooltip" title="Ver" class="btn btn-primary mb-1"><i class="bi bi-list-task"></i></a>
            {{-- <button data-toggle="tooltip" title="Comparar" class="btn btn-primary mb-1" onclick="comparar({{$ronda->id}})"><i class="bi bi-map"></i></button> --}}
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  {{-- @can('administrar_sistema') --}}
  <form id="form_delete_ronda" method="POST"> @csrf @method('DELETE') </form>
  <form id="form_cerrar_ronda" method="POST"> @csrf @method('PATCH') </form>
  {{-- @endcan --}}
  @endsection

  @section('scripts')
  <script type="text/javascript">
    var url_base = "{{route('ronda.comparar', ['ronda' => '__ronda', 'circuito' => '__circuito'])}}";
    var btn_ronda_create = document.getElementById('btn_ronda_create');
    btn_ronda_create.addEventListener('click', crear_rondin);

    function crear_rondin(){
      let opciones = document.getElementById('select_circuito').length;
      if(opciones > 1){
        var modal = new bootstrap.Modal(document.getElementById('mdl_ronda_create'));
        modal.show();
      }else{
        var form = document.getElementById('form_ronda_create');
        form.submit();
      }
    }

    function ver_circuito(url){
      location.href = url;
    }

    $(document).ready(function(){
      $('#tabla').DataTable({
        language : {
          url : '{{asset('assets/dt.spanish.json')}}'
        },
        order: [[1, 'desc']],
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
        let opciones = document.getElementById("select_circuito").options.length;
        /*Si hay una sola opcion, que sea directamente un redirect*/
        if(opciones == 1){         
          let url = url_base.replace('__ronda', ronda);
          url = url.replace('__circuito', circ);
          location.href = url;
        }else{
          $('#mdl_ronda_comparar').modal('show');
          url = url.replace('__circuito', circ);
          document.getElementById('btn_url_comparar').setAttribute('href', url);
        }
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
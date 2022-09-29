@extends('layouts.app')
@section('content')
@section('titulo', 'Usuarios - ')
<div class="container">
  <x-misc-title title="Usuarios">
    <a href="{{route('user.create')}}" class="btn btn-success text-white" data-toggle="tooltip" title="Agregar Usuario"><i class="bi bi-person-plus-fill"></i></a>
  </x-misc-title>
  <div class="table-responsive">
    <table class=" table table-hover table-sm" id="tabla" data-page-length='25'>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Correo Electrónico</th>
          <th>Opciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($users as $user)
        <tr>
          <td>{{ ucwords($user->nombre) }} {{ucwords($user->apellido)}}</td>
          <td>{{ $user->email }}</td>
          <td>
            <a class="btn btn-warning btn-sm" data-toggle="tooltip" title="Editar" href="{{route('user.edit', $user)}}"><i class="bi bi-pencil-fill"></i></a>        
            @if($user->id !== Auth::user()->id)
            <button data-toggle="tooltip" title="Eliminar Usuario" class="btn btn-danger btn_delete_user btn-sm" data-url="{{route('user.destroy', $user->id)}}">
              <i class="bi bi-trash"></i>
            </button>
            @endif 
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
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
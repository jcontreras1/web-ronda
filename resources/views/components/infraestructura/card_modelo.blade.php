<div class="col-12 col-sm-4">
<div class="card indigo">
  <div class="card-body">
    <div class="display-2"><i class="bi bi-gear"></i></div>
    <div class="display-5">{{ucfirst($modelo->nombre)}}</div>
    <div class="py-2"></div>
    <button data-nombre="{{$modelo->nombre}}" data-url="{{route('modelo.update', $modelo)}}" class="btn btn-outline-warning edit_modelo">Editar</button>
    <button data-url="{{route('modelo.destroy', $modelo)}}" class="btn btn-outline-danger eliminar_modelo">Eliminar</button>
  </div>
</div>
</div>
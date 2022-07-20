<div class="col-sm-3 py-2">
    @if(variable_global('USAR_COLORES_SALIDA') == 1)
    <div class="card h-100" style="border-left: 12px solid {{$salida->color}};">
        @else
        <div class="card info h-100">
            @endif
            <a href="{{route('venta.create', $salida)}}" style="text-decoration: none;" class="text-dark">
                <div class="card-body @if($salida->cerrada) text-muted @endif">
                    <div class="card-title h1">{{date('H:i', strtotime($salida->hora))}}</div>
                    <div class="card-title h3">{{ucfirst($salida->embarcacion->nombre)}} <strong>|</strong> <small class="text-muted h5"><em>Capacidad: {{$salida->embarcacion->capacidad}}</em></small></div>
                    <div class="card-title h5">Disponibles: <span class="text-muted">{{$salida->disponibilidad}}</span></div>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="{{100*(1-($salida->disponibilidad/$salida->embarcacion->capacidad))}}" aria-valuemin="0" aria-valuemax="100" style="width: {{100*(1-($salida->disponibilidad/$salida->embarcacion->capacidad))}}%"></div>
                    </div>
                </div>
            </a>
            <div class="card-footer">
                @if(!$salida->cerrada)
                <a class="btn btn-outline-success" data-toggle="tooltip" title="Venta" href="{{route('venta.create', $salida)}}"><i class="h3 bi bi-credit-card-fill"></i></a>
                <button class="btn btn-outline-danger btn_salida_cerrar" data-url="{{route('salida.update', $salida)}}" data-toggle="tooltip" title="Cerrar"><i class="h3 bi bi-file-earmark-lock2"></i></button>
                @else
                <button class="btn btn-warning btn_salida_abrir" data-url="{{route('salida.update', $salida)}}" data-toggle="tooltip" title="Abrir"><i class="h3 bi bi-file-check"></i></button>
                @endif
                <a class="btn btn-outline-primary" href="{{route('rol.edit', $salida->rol)}}" data-toggle="tooltip" title="Rol"><i class="h3 bi bi-list-task"></i></a>
            </div>
        </div>
    </div>
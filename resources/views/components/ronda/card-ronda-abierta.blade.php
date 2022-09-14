<div class="col-12 col-md-4">
	<div class="card" style="background-color: #BBBBBB;">
		<div class="card-body">
			<a class="text-dark" href="{{route('ronda.show', $ronda)}}" style="text-decoration: none;">
				<div class="card-title">
					<h5>Ronda #{{$ronda->id}}</h5>
					<hr>
					<div>
						
					Fecha: <strong>{{ date('d/m/Y', strtotime($ronda->created_at)) }} </strong> a las <strong>{{ date('H:i', strtotime($ronda->created_at)) }}</strong>
					</div>
					<div>
					Por: <strong>{{ ucwords($ronda->creador->nombre . ' ' . $ronda->creador->apellido) }}</strong>
					</div>
					@if(count($ronda->checkpoints) == 0)
					<small><em>Sin datos</em></small>
					@else
					<em>{{count($ronda->checkpoints)}} puntos de control</em>
					@endif
				</div>
			</a>
		</div>
		<div class="card-footer">
			<span class="float-end">
				@if(Auth::user()->id == $ronda->creador->id)
				<button data-toggle="tooltip" title="Cerrar ronda" class="btn btn-warning btn_cerrar_ronda" data-url="{{route('ronda.cerrar', $ronda)}}"><i class="bi bi-check2"></i></button>
				<button data-toggle="tooltip" title="Eliminar ronda" class="btn btn-danger btn_delete_ronda" data-url="{{route('ronda.destroy', $ronda)}}"><i class="bi bi-trash"></i></button>
				@endif
			</span>
		</div>
	</div>
</div>
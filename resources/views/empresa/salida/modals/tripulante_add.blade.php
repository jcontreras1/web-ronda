<div class="modal" id="mdl_salida_tripulante_add" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form method="POST" action="{{route('tripulante.store')}}">
			@csrf
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Agregar personal a la Salida</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="id_vehiculo_salida" name="vehiculo">
					<div class="row">
						<div class="col-12">
						<label>Persona</label>
							<select class="primerCampo form-select @if($errors->has('persona')) is-invalid @endif" name="persona" required>
								@foreach($tripulantes_disponibles as $tripulante)
								<option value="{{$tripulante->id}}">
									{{$tripulante->lastname}} - {{$tripulante->name}}  
								</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
						<label>Profesion</label>
							<select class="form-select @if($errors->has('profesion')) is-invalid @endif" name="profesion" required>
								@foreach($tipo_tripulante as $tipo)
								<option value="{{$tipo->id}}">
									{{$tipo->nombre}} 
								</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-lg btn-success">Aceptar</button>
					<button type="button" class="btn btn-lg btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</form>
	</div>
</div>
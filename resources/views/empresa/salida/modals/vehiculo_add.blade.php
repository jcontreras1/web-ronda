<div class="modal" id="mdl_salida_vehiculo_add" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form method="POST" action="{{route('vehiculo_salida.store')}}">
			@csrf
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Agregar vehículo a la Salida</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="id_salida" value="{{$salida->id}}">
					<div class="row">
						<div class="col-12">
						<label>Vehículo</label>
							<select class="primerCampo form-select @if($errors->has('id_vehiculo')) is-invalid @endif" name="id_vehiculo" required>
								@foreach($vehiculos as $vehiculo)
								<option value="{{$vehiculo->id}}">{{$vehiculo->dominio}} - 
									{{$vehiculo->get_modelo->marca->nombre}} {{$vehiculo->get_modelo->nombre}} ({{$vehiculo->capacidad}} ocupantes)
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
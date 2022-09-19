<div class="modal" id="mdl_area_add" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form method="POST" action="{{ route("area_usuario.store", ['user' => $user]) }}">
			@csrf
			@method('POST')
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Agregar Área</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							 <label>Area</label>
							 <div class="py-1"></div>
							<select name="area_id" class="form-select primerCampo">
								@foreach($areas as $area)
									<option value="{{$area->id}}"> {{ strtoupper($area->nombre) }} </option>
								@endforeach
							</select>
							<br/>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success">Aceptar</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</form>
	</div>
</div>
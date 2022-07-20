<div class="modal" id="mdl_cargo_add" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<form id="form_add_rol" method="POST" action="{{ route("cargo.store", ['user' => $user]) }}">
			@csrf
			@method('POST')
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Agregar/Modificar Cargo en la Empresa</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							 <label>Cargo</label>
							 <div class="py-1"></div>
							<select name="cargo" class="form-select primerCampo">
								@foreach($tipos_usuario as $tipo)
									<option value="{{$tipo->id}}"> {{ $tipo->nombre }} - {{$tipo->descripcion}} </option>
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
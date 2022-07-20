<div class="modal" id="mdl_modelo_create" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form id="form_modelo_edit" method="POST" action="{{route('modelo.store')}}">
			@csrf
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Crear Modelo</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<input type="hidden" name="id_marca" value="{{$marca->id}}">
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
						<label>Modelo</label>
							<input autocomplete="off" type="text" class="primerCampo form-control @if($errors->has('nombre')) is-invalid @endif" name="nombre" required>
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
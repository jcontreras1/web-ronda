<div class="modal" id="mdl_tarifa_create" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<form method="POST" action="{{ route("tarifa.store") }}">
			@csrf
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Crear tarifa</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							<label>Nombre @include('components.misc.required')</label>
							<input type="text" name="nombre" class="form-control primerCampo" autocomplete="off" required>
						</div>
						<div class="col-12">
							<label>Importe @include('components.misc.required')</label>
							<div class="input-group">
								<span class="input-group-text">$</span>
								<input type="number" name="importe" class="form-control" required>
							</div>
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